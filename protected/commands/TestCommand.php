<?php

class TestCommand extends CConsoleCommand {

    /**
     * 初始化
     * @see CConsoleCommand::init()
     */
    public function init() {
        parent::init();
    }

    /**
     * @param int $monitor_id 监控策略ID
     * 报警入口
     */
    public function run($id) {
        print_r(Request::init()->url('http://localhost/Althea/index.php?r=monitor/list')->post(array('test'=>'abc')));
    }

} 