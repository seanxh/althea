#!/usr/bin/env python
# -*- coding: UTF-8 -*-
import database,os,sys
import multiprocessing
import commands
import time
import ConfigParser

'''
    @version : 2013-09-29 1.0
    @author : xuhao05
    @todo : auto excute the multi api monitor shell commands
    '''


CURRENT_DIR = os.path.split(os.path.realpath(__file__))[0]

YIIC = "/protected/yiic monitor --id="


##数据库设置
dirname, filename = os.path.split(os.path.abspath(sys.argv[0]))
config_filename = dirname+'/protected/config/config.ini'
cfg = ConfigParser.ConfigParser()
cfg.read(config_filename)
if not cfg.has_section('database') :
    print config_filename + ' missing [database] section'
    sys.exit()

if not cfg.has_section('monitor') :
    print config_filename + ' missing [monitor] section'
    sys.exit()

try :
    MYSQL_HOST = cfg.get('database','HOST')+':'+cfg.get('database','PORT')
    MYSQL_DB = cfg.get('database','DBNAME')
    MYSQL_USER = cfg.get('database','USER')
    MYSQL_PASS = cfg.get('database','PASSWD')
    #php-cli 所在目录
    PHP_CLI = cfg.get('monitor','CLI_PATH')
    #脚本执行日志，记录的文件
    LOG_PATH = cfg.get('monitor','LOG_PATH')
    LOG_PATH += "/monitor_"+time.strftime('%Y%m%d')+".log"
except ConfigParser.NoOptionError,e:
    print 'Config file(' + config_filename + ') missed config item "' + e.option + '"'
    sys.exit()

global db
db = database.Connection(host=MYSQL_HOST,
                         database=MYSQL_DB,user=MYSQL_USER,
                         password=MYSQL_PASS)

db.execute('set names utf8')


'''
    获取所有监控策略的报警周期
    @return dict rule_cycle { rule_id : cycle(/s) }
    '''
def calc_monitor_cycle():
    monitor_rules_models = db.monitor_rule
    monitor_rules = monitor_rules_models.where(monitor_rules_models.status==1).select()
    # log_model = db.log_config
    # logs = log_model.select()
    
    # logs_dict = {}
    # for log in logs:
    # logs_dict[log.id] = log
    
    #gt_1min_rules = {}
    #lt_1min_rules = {}
    rules_cycle = {}
    
    for rule in monitor_rules:
        # if rule.is_alert_everytime == 1:
            # cycle = logs_dict[ rule.log_id ].log_cycle
            # cycle = rule.cycle
        # else:
            # cycle = int(logs_dict[ rule.log_id ].log_cycle)* int(rule.alert_in_cycles)
            # cycle = int(rule.cycle)* int(rule.alert_in_cycles)
        #if cycle >= 60 :
        cycle = rule.cycle
        rules_cycle[rule.id] = cycle
    #else:
    #    lt_1min_rules[rule.id] = cycle
    print rules_cycle
    return rules_cycle


class ForkMonitor(multiprocessing.Process):
    def __init__(self,rules,time_stamp):
        multiprocessing.Process.__init__(self)
        self.daemon = True #如果设置此参数，则为后台线程
        self.rules = rules
        self.time_stamp = time_stamp
        self.shell = CURRENT_DIR+YIIC
    
    def run(self):
        for rule in self.rules:
            if self.time_stamp % self.rules[rule] == 0:
                start = time.time()
                (status,result) = commands.getstatusoutput(self.shell+str(rule))
                end = time.time()
                exec_time = round(end-start,3)
                log = open(LOG_PATH, 'a')
                log.write("%s|%s|%f|%d|%s\n" % (time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time())), self.shell+str(rule), exec_time, status, result ) )
                log.close()


#自动生成这些 shell 命令，并fork进程去执行，然后退出
# /opt/www/monitor.api.rms.baidu.com/service/mon_common_api/daemon/crontab/cron_mon_api.php rms 1 1
if __name__ == '__main__' :
    start = (int(time.time())//60)*60
    rules_cycle= calc_monitor_cycle()
    
    process_pool = []
    
    gt_1min_rules = {}
    lt_1min_rules = {}
    
    monitor = ForkMonitor(rules_cycle,start)
    monitor.start()
    process_pool.append( monitor )
    
    for rule in rules_cycle:
        if rules_cycle[rule] >=60:
            gt_1min_rules[rule] = rules_cycle[rule]
        else:
            lt_1min_rules[rule] = rules_cycle[rule]

    while (time.time() - start ) < 60 :
        seconds = int( time.time() )
        monitor = ForkMonitor(lt_1min_rules,seconds)
        monitor.start()
        process_pool.append( monitor )
        time.sleep(1)


    for process in process_pool:
        process.join()