<?php
require_once 'Core/Controller/Block.php';
class Addons_Poll_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function getPollAction(){
    $this->view->poll = $this->_module->getModel('Poll')->get_poll();

    if ( ! empty($this->view->poll) ){
      App::header()->addCode('<script type="text/javascript" src="https://www.google.com/jsapi"></script>');
      App::header()->addCode('<script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});

        function drawChart(chart_title, chart_values) {
          var data = new google.visualization.DataTable();
          data.addColumn("string", "Opinion");
          data.addColumn("number", "Votes");
          data.addRows(chart_values);

          var options = {
            width: 300, height: 250,
            legend: {position: "none"},
            title: chart_title,
            backgroundColor: "transparent"
          };

          var chart = new google.visualization.PieChart(document.getElementById("poll"));
          chart.draw(data, options);
        }
      </script>');


      App::header()->addScript( App::url()->get('/poll.js','js') );
      App::header()->addCode("
          <script type='text/javascript'>
          </script>");
    }

  }

}