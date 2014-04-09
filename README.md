# Althea


------

Althea是一款开源的日志分析报警和图表展示系统。  
Althea 力求解决更多的可通用化的日志报警和图表汇总需求。  
Authored by @seanxh 

Althea特性：

> * 周期性的和非周期性的日志监控报警功能
> * 丰富可扩展的表达式
> * 丰富的可配置性图表
> * 可跨域加载的实时和周期性图表


------

### 监控1




**如果你有一个这样的表** 

|id| name | value | status|
|------ | -----|----------|--------|
|1  | test | 2 | 0|
|2     | test2 | 4 | 1|
|3      | test3 | 5 | 0|

你想要监控这个表，并且每分钟把status不为0的值报警。

那你需要：
>* **注册一个表:** table1
>* **编写一个SQL:** select * from TABLE where status!=0
>* **一个报警模板:** [$name] : [$value] status: [$status]

### 监控2
**如果你有一个这样的表** 

id      | name | value | status | ctime
--------- | ------|-------- | -----|-------
1  | test | 20 |  20 | 2014-04-09 11:00:00
2  | test | 2 | 5 | 2014-04-09 11:01:00

这个表是周期性的，你想要监控这个周期比上个周期大10的情况。
那你需要：
>* **设定表周期** table2 60s
>* **编写一个SQL** select * from TABLE querygroup by name
>* **设定报警条件** $name - prev(name) > 10
>* **一个报警模板** [$name] : [$name - prev(name)]


#SQL
SQL和报警条件的一些规则是Althea所特有的。
SQL基本是遵循Mysql的SQL语法。其中：
> - select * from TABLE  
当一个周期型日志时，Althea会自动为你追加上where 条件。所以当前周期为：
select * from TABLE where ctime>='2014-04-09 13:00:00' and ctime<'2014-04-09 13:01:00'
> - select * from **TABLE** querygroup by name  
中的TABLE指的是这个报警条件关联到了哪个表。而当你配置这个表时，可以在表名称中有丰富的设置。比如:status_[dateDayFormat()] 会得到一个按天变化的表：status_20140409
> - select * from TABEL **querygroup by name**  
中的querygroup by 是特定的Althea语法。表示此SQL取出的数据在程序中会是这样：
```
array(
    'test'=>array('id'=>1,name=>test,value=>20,stauts=>0,ctime=>'2014-04-09 11:00:00',
    'test2'=>array('id'=>2,name=>test2,value=>20,stauts=>0,ctime=>'2014-04-09 11:00:00',
)
```
> - select * from TABLE where name=**[function()]**  
同时在SQL中的[]括起来的部分，会被当作一个**表达式**执行。

#表达式
**变更和字符串：** 凡是以$开头的都是变量。否则为字符串。如 $name name  
**函数:** 如果以字母开头，且后跟()就是函数。prev(name),hour(),dataDayFromat()  
**数组:** 数组有两种形式。{name:\$name,abc:cc}, array(name:$name,abc:cc)  
**比较运算符:** >,==(或=),<,>=,<=,!=,in  
**布尔运算符:** &&(或&),||(或|),and,or  
例子： 
($value-prev(value)) > 200 && $name in {test,test2}

#SQL和表达式

select * from **TABLE** querygroup by name 取出的数据是这样的： 

id      | name | value | status | ctime
------ | -----|------ | -----|---------
1  | test | 20 |  20 | 2014-04-09 11:00:00
2  | test2 | 20 |  20 | 2014-04-09 11:00:00

Althea会遍历所有的当前周期的数据，并用表达式进行匹配，然后判断出结果。  
比如其中一条记录是：  

id      | name | value | status | ctime
--------- | -----|--------- | -----|---------
1  | test | 20 |  20 | 2014-04-09 11:00:00

而表达式是**($value-prev(value)) > 200 && $name in {test,test2}**：
> - $value指的是当前周期中的value=20，而prev(value)则指的是时间为2014-04-09 10:59:00，name为test的value=5
> - $name则必须是数组中的某个值，此处则为test。
此条记录满足所有的条件，则标记为可以报警项。

#图表
图表的配置很简单，
>- 一个SQL
>- 一个计算表达式。像：$value-prev(value)

引用图表：
```
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>
		
		<script type="text/javascript" src="./assets/jquery.min.js"></script>
		<script type="text/javascript" src="./assets/althea.js"></script>
		<script src="./assets/highcharts/highcharts.js"></script>


		<script type="text/javascript">
			$(function () {
			        Althea.charts({
				        'container' : $('#container'),
				        'chart' : 2 ,
			        });
			});
		</script>

	</head>
	<body>
	<div id="container" style="width:800px; height: 500px; margin: 0 auto"></div>
	</body>
</html>
```
其中charts函数，指定container也即图表要放置的DOM节点。chart则指定你在Althea注册的图表的ID

----------------------
```
$(function () {
        Althea.charts({
	        'container' : $('#container'),
	        'chart' : 2 ,
        });
});
```
------------------------------
会触发一个JSONP请求，到Althea，这也就意味着你可以在任意域下引用Althea的图表。
