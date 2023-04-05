<!DOCTYPE html>
<meta charset="utf-8">
<html >
  <head>
  
  </head>
<link rel="stylesheet" type="text/css" href="css/sensors.css">
<link rel="stylesheet" href="css/bootstrap.min.css">

<script src="js/jquery.min.js"></script>
<script src="js/socket.io.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src='js/heatmap.min.js'></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- link to gradient -->
 <script src='http://cdn.zingchart.com/zingchart.min.js'></script>
 <script src="https://cdn.zingchart.com/zingchart.min.js"></script>

<script type="text/javascript" src="js/Chart.min.js"></script>

<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js" integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.0.1/chartjs-plugin-zoom.min.js" integrity="sha512-b+q5md1qwYUeYsuOBx+E8MzhsDSZeoE80dPP1VCw9k/KA9LORQmaH3RuXjlpu3u1rfUwh7s6SHthZM3sUGzCkA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
-->
<script>
  /*
  setinterval(function(){
    var currentTime = new Date();
    var currentHour = currentTime.getHours();
    var currentMinute = currentTime.getMinutes();
    var currentSecond = currentTime.getSeconds();

    if(currentHour == 00 && currentMinute == 00 && currentSecond == 01){
      location.reload();
    }
  }, 1000);*/
</script>
<script>
  window.onload = function() {
    var c = document.getElementById("canvasHeat");
    var ctx = c.getContext("2d");
    var img = document.getElementById("planta");
    ctx.drawImage(img, 10, 10);
  }
</script> 

<style>
  .buttonSensor.selected{
    background-color: white;
    color:#555555;
}
.typeButton.selected{
    background-color: white;
    color:#555555;
    border:  2px solid #555555;
}
body{
  width: 100%;
}
.buttonSensor{
  
}


</style>

<body>

<?php

  include('nav.php');
  include('monthdb.php');

  header("refresh: 10000;");  

?>

  <div class="container-fluid page-container" >
    <div class="row dashboard-container" >

      <div class="col-12" style="margin-top: 7px;">
        <div class="row dashboard-rows"> 
          <div class="col-md-12 pr-md-1" >
            <div class="graph-containers" style="">

              <div class="Wrapper" style="display: inline-block; float: left; width: 85%; margin-top: 0px; overflow:auto ;">

                <div id='heatMap'  style=" width: 100%; min-height: 552px; height: 66vh;  padding: 0px;  margin: 0 auto; display: block;">
                <img  id="planta" src='images/plantaV2.png' max-width="100%" height="auto" />
                <canvas id="canvasHeat" width="1290"  style="position:absolute; left: 0; top: 0">
                </canvas> 
              </div>
              <div class="tooltip" style="position: absolute; left: 0; top: 0; background: rgba(0,0,0,.8); color: white;
              font-size: 14px; padding: 5px; display: block; line-height: 18px; display:none;"></div>
            </div>


            <div style="display: inline-block; float: left; width: 15%; height: 100%;  display: flex; flex-direction: row; flex-wrap: wrap; justify-content: center; align-items: center; ">
             <div id='GradTemperature' style="width: 150px; height: 600px; margin-top: 10%; margin-bottom: -10%">
            </div>

            <!--552 1280 -->
          </div>
        </div>
      </div>
    </div>

    <div class="row dashboard-rows"  style="height: 23vh; ">
      <div class="col-md-12 pr-md-1">
        <div class="graph-containers" >
          <div style="display: inline-block; float: left; width: 3%;"><br></div>
            <div style="display: inline-block; float: left; width: 22%; margin-top: 8px; margin-bottom: 8px;">
              <div style="margin: 0 auto; text-align: center; width:40vh; overflow:auto ; height: 260px;">

               <?php  
               require 'php/connect.php';
                //error_reporting(0); 
               $mysqli = new mysqli("$servername", "$username", "$password", "$dbname");

               if ($mysqli->connect_errno) {
                echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
                exit();
                }

                $i=0;

                $sql = "SELECT count(location_id) FROM `location` where status = 1;";  
                $result = $mysqli->query($sql);
                $total = mysqli_fetch_array($result);
                
                $total= substr(implode($total),-2);
              
                
                ?>
                <table width='100%'>
                  <tr>
                    <td valign="top" ><?php 
                    $sql = "SELECT distinct id_sensor FROM `location` where status = 1 order by id_sensor limit ".round($total/3).";";  
                    $result = $mysqli->query($sql);
                    while($row = mysqli_fetch_array($result)){
                      echo '<button style="margin-bottom: 10px;" class="btn btn-md btn-secondary btn-block buttonSensor" onClick="SeeSensor(this.id)" id='. $row["id_sensor"].'  >Nó '. $row["id_sensor"].'</button>';
                    }
                    
                    ?></td>
                    <td valign="top" ><?php
                    $sql = "SELECT distinct id_sensor FROM `location` where status = 1 order by id_sensor limit ".round($total/3)." offset ".round($total/3).";";  
                    $result = $mysqli->query($sql);
                    while($row = mysqli_fetch_array($result)){
                      echo '<button style="margin-bottom: 10px;" class="btn btn-md btn-secondary btn-block buttonSensor" onClick="SeeSensor(this.id)" id='. $row["id_sensor"].'  >Nó '. $row["id_sensor"].'</button>';
                    }
                    ?></td>
                    
                    <td valign="top" ><?php
                    $sql = "SELECT distinct id_sensor FROM `location` where status = 1 order by id_sensor limit 100 offset ".(round($total/3)*2).";";  
                    $result = $mysqli->query($sql);
                    while($row = mysqli_fetch_array($result)){ 
                      echo '<button style="margin-bottom: 10px;" class="btn btn-md btn-secondary btn-block buttonSensor" onClick="SeeSensor(this.id)" id='. $row["id_sensor"].'  >Nó '. $row["id_sensor"].'</button>';
                    }
                    ?></td>
                  </tr>
                </table>
                <?php
                //echo '<button class="btn btn-md btn-secondary btn-block buttonSensor" onClick="SeeSensor(this.id)" id='. $row["id_sensor"].'  >Nó '. $row["id_sensor"].'</button>';

                ?>

              </div>
            </div>
            <div style="display: inline-block; float: left; width: 3%;"><br></div>

            <!-- aqui fica o grafico -->
            <div  style="display: absolute; float: left; width: 65%; margin-left: 30px;">
          
              <div style="margin: 0 auto; text-align: center; width: 80%; margin-top: 5px; margin-bottom: 0px;">

              <button type="button" class="btn btn-secondary typeButton" onClick="SeeMeasure('temperature');" id="temperature" style="width: 150px; padding: 0.3px;" >Temperatura</button>
              <button type="button" class="btn btn-secondary typeButton" onClick="SeeMeasure('humidity');" id="humidity"style="width: 150px; padding: 0.3px;">Humidade</button>
              <button type="button" class="btn btn-secondary typeButton" onClick="SeeMeasure('pressure');" id="pressure" style="width: 150px; padding: 0.3px;">Pressão</button>
              <button type="button" class="btn btn-secondary typeButton" onClick="SeeMeasure('co2');" id="co2"style="width: 150px; padding: 0.3px;">CO2</button>
              <button type="button" class="btn btn-secondary typeButton" onClick="SeeMeasure('tvoc');" id="tvoc" style="width: 150px; padding: 0.3px;">TVOC</button>
                

            </div>  

            <div class="contentor">
              <canvas id='ChartLine'> {{ chart }}></canvas>
            </div>
            <!--style="max-height: 35vh; margin-left: 20px"-->
          </div>
        </div>  
        
      </div>



    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
</body>
<style>
  #ChartLine{
    height: 250px;
  }
@media screen and (max-width: 1300px){
  .contentor{
    margin-left: 50px;
    margin-top: 10px;
    height: 200px;
  }
  #planta{
    height: 430px;
    width: auto;
  }
}

</style>


<script> 
 

  document.addEventListener("DOMContentLoaded", function(event) { 
    document.getElementById("0101").click();
  });

  $('.buttonSensor').on('click', function(){
    $('.buttonSensor').removeClass('selected');
    $(this).addClass('selected');
  });

  document.addEventListener("DOMContentLoaded", function(event) { 
    document.getElementById("temperature").click();
  });

  $('.typeButton').on('click', function(){
    $('.typeButton').removeClass('selected');
    $(this).addClass('selected');
  });


  var dataTemperatura = [];
  var dataHumidity =  [];
  var dataPressure = [];
  var dataCO2 = [];
  var dataQA = [];
  var dataHour = [];

   var socket = io.connect('http://localhost:8080');

      socket.emit('dbRequestTemp');
      
      var sensorId = '0129';

      //pedido update
      socket.on('dbUpdated', function(status){
        console.log("A base de dados foi atualizada!!!!!!");

        socket.emit('dbRequestMeasure', sensorId);
        socket.emit('dbRequestTemp');
      });
      //rececao dos dados do gráfico
      socket.on('dbNew', function(db){
      console.log('Dados recebidos!');

        dataTemperatura = db.map(sensor => sensor.temperature);
        dataHumidity = db.map(sensor => sensor.humidity);
        dataPressure = db.map(sensor => sensor.pressure);
        dataSensor = db.map(sensor => sensor.id_sensor);
        dataHour = db.map(sensor => sensor.hour);
        dataQA = db.map(sensor => sensor.eCO2);
        dataTVOC = db.map(sensor => sensor.eTVOC);
        dataMinT = db.map(sensor => sensor.MinT);
        dataMaxT = db.map(sensor => sensor.MaxT);
        dataMinH = db.map(sensor => sensor.MinH);
        dataMaxH = db.map(sensor => sensor.MaxH);
        dataMinP = db.map(sensor => sensor.MinP);
        dataMaxP = db.map(sensor => sensor.MaxP);
        dataMinC = db.map(sensor => sensor.MinC);
        dataMaxC = db.map(sensor => sensor.MaxC);
        dataMinV = db.map(sensor => sensor.MinV);
        dataMaxV = db.map(sensor => sensor.MaxV);

        dataHour = dataHour.reverse();
        dataTemperatura = dataTemperatura.reverse();
        dataHumidity = dataHumidity.reverse();
        dataPressure = dataPressure.reverse();
        dataQA = dataQA.reverse();
        dataTVOC = dataTVOC.reverse();
        dataMinT= dataMinT.reverse();
        dataMaxT = dataMaxT.reverse();
        dataMinH = dataMinH.reverse();
        dataMaxH = dataMaxH.reverse();
        dataMinP = dataMinP.reverse();
        dataMaxP = dataMaxP.reverse();
        dataMinC = dataMinC.reverse();
        dataMaxC = dataMaxC.reverse();
        dataMinV = dataMinV.reverse();
        dataMaxV = dataMaxV.reverse();
        
      //HOVER EVENT ON GRAPHIC
      dataSensor.forEach(function(item, index) {
      // buttonNumber = dataSensor.indexOf(dataSensor[i]);
      $('#' + item).hover(function() {
        hoverEvent(index);
        console.log('skjdfahbedfv', index);
      });
    });
    //END OF HOVER EVENT

        UpdateGraph(SensorMeasure);
      });

      //rececao de dados heatmap
    socket.on('dbTemp', function(db){
    console.log('Dados recebidos!', db);

    testData.data = [];

    db.forEach(sensor => {
      testData.data.push({x: sensor.location_x, y: sensor.location_y, value: sensor.temperature,  radius: 80});
    });

    //heatmapInstance.setData(testData);
        //UpdateGraph();
      });

    function SeeMeasure(measure){

      SensorMeasure = measure;


     UpdateGraph(SensorMeasure);

    }


  function UpdateGraph(SensorMeasure){
   
   
      var ValorMinimo = 0;
      var ValorMaximo = 0;

     if(SensorMeasure=="temperature"){
          DataMeasure = dataTemperatura;
          DataMin = dataMinT;
          DataMax = dataMaxT;
          ValorMinimo = Math.min.apply(this, DataMin) - 10;
          stepSize: 0.5;
          ValorMaximo = Math.max.apply(this, DataMax) + 10;
        } else if (SensorMeasure=="humidity"){
          DataMeasure = dataHumidity;
          DataMin = dataMinH;
          DataMax = dataMaxH;
          ValorMinimo = Math.min.apply(this, DataMin) - 10;
          stepSize: 0.5;
          ValorMaximo = Math.max.apply(this, DataMax) + 10;
        } else if (SensorMeasure=="pressure"){
          DataMeasure = dataPressure;
          DataMin = dataMinP;
          DataMax = dataMaxP;
          ValorMinimo = Math.min.apply(this, DataMin) - 10;
          stepSize: 0.5;
          ValorMaximo = Math.max.apply(this, DataMax) + 10;
        } else if (SensorMeasure=="co2"){
          DataMeasure=dataQA;
          DataMin = dataMinC;
          DataMax = dataMaxC;
          ValorMinimo = Math.min.apply(this, DataMin) - 10;
          stepSize: 0.5;
          ValorMaximo = Math.max.apply(this, DataMax) + 10;
        } else if (SensorMeasure=="tvoc"){
          DataMeasure=dataTVOC;
          DataMin = dataMinV;
          DataMax = dataMaxV;
          ValorMinimo = Math.min.apply(this, DataMin) - 10;
          stepSize: 0.5;
          ValorMaximo = Math.max.apply(this, DataMax) + 10;
        }
        
   
 // chart
  

      
      var ctx2 = document.getElementById('ChartLine').getContext('2d');
      var myChart;
      if (typeof myChart !== "undefined") {
        myChart.destroy();
      }
      if (myChart) myChart.destroy();
      myChart = new Chart(ctx2, {
        type: 'line',
        data: {
          labels: dataHour,
          datasets: [
          {
              data: DataMeasure,
              backgroundColor: '#3F87CE',
              hoverBackgroundColor:"#ffff",
              pointBackgroundColor: "#3F87CE",
              pointBorderColor: '#fff',
              borderColor: '#3F87CE',
              hoverBorderColor:"#3F87CE",
              fill: false
            }        
            ]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
            legend:{
              display: false
            },
            scales: {
              xAxes: [{
                display:true
              }],
              yAxes: [{
                display: true,
                ticks: {
                  min: ValorMinimo,
                  max: ValorMaximo
                }
              }]
            }
          }
        });

      

       console.log("dataTemperatura", dataTemperatura);
    }

   /////// SOCKETS
   window.onload = SeeSensor('0101');
   //window.onload = UpdateGraph();
    
    function SeeSensor(clicked_id ){

      sensorId = clicked_id;


      socket.emit('dbRequestMeasure', sensorId);

    }





///criacao do heatmap

var heatmapInstance = h337.create({
  container: document.getElementById('heatMap')
});



var testData = {
  min: 0,
  max: 35,
  
  
  data: [
        {x:location.location_x, y: 400, value: 3.5,  radius:80, text: 101}, //sensor 1
        {x:location.location_x, y:location.location_y, value: 7,  radius:80}, //sensor 2
        {x: 1050, y: 270, value: 10.5,  radius:80}, //sensor 3
        {x: 940, y: 270, value: 14,  radius:80}, //sensor 4
        {x: 840, y: 300, value: 17.5,  radius:80}, //sensor 5
        {x: 710, y: 320, value: 21,  radius:80}, //sensor 6
        {x: 480, y: 310, value: 24.5,  radius:100}, //sensor 7
        {x: 290, y: 300, value: 28,  radius:100}, //sensor 8
        {x: 610, y: 390, value: 31.5,  radius:100} //sensor 9
        ]
      };
      heatmapInstance.setData(testData);
      
      
      
///////////////////////////////

var demoWrapper = document.querySelector('.Wrapper');
var tooltip = document.querySelector('.tooltip');
function updateTooltip(x, y, value) {
  // + 15 for distance to cursor
  var transl = 'translate(' + (x + 15) + 'px, ' + (y + 15) + 'px)';
  tooltip.style.webkitTransform = transl;
  tooltip.innerHTML = value;
};
demoWrapper.onmousemove = function(ev) {
  var x = ev.layerX;
  var y = ev.layerY;
  // getValueAt gives us the value for a point p(x/y)
  var value = heatmapInstance.getValueAt({
    x: x, 
    y: y
  });
  

  actualTemperature = value;
  //TempSensor = text;

  renderTemperatures();

  tooltip.style.display = 'block';
  updateTooltip(x, y, value);
};
// hide tooltip on mouseout
demoWrapper.onmouseover = function() {
  tooltip.style.display = 'none';
};

/* tooltip code end */





////////////////LEGEND
    var actualTemperature = 0;

     var GradTemperature;

renderTemperatures();

function renderTemperatures() {
  var GradTemperature = {
                  "graphset": [
        {
            "type": "mixed",
            "background-color":"none",
            "scale-x":{
                "visible":0
            },
            "scale-y":{
                "guide":{
                    "visible":0
                },
                "tick":{
                    "line-color":"#A8A8A8",
                    "line-width":1
                },
                "line-width":1,
                "line-color":"#A8A8A8",
                "values":"0:35:1",
                "format":"%v°C",
                "markers":[
                    {
                        "type":"line",
                        "range":5
                    }    
                ]
            },
            "tooltip":{
                "visible":0
            },
            
            "plot":{
                "bars-overlap":"100%",
                "hover-state":{
                    "visible":0
                }
            },

            // labels:[
            //   {
            //     text:"Sensor 1",
            //     x:"15%", 
            //     y:"1%",
            //     fontSize:"22px",
            //     borderWidth:"1px",
            //     padding : "5px",
            //     borderRadius : 10
            //   }
            // ],
            "series": [
                {
                    "type":"bar",
                    "values": [35],
                    "gradient-colors":"#e6e6ff #d4d4ff #b3c0f3 #99cdcc #80ea96 #80ff66 #a5ff4d #ddff33 #ffb91a #ff0300",
                    "gradient-stops":"0.1 0.2 0.3 0.4 0.5 0.6 0.7 0.8 0.9 1",
                    "fill-angle":-90
                },
                {
                  "type":"scatter",
                  "values":[actualTemperature],
                  "marker":{
                    "type":"rectangle",
                    "height":3,
                    "width":"20%"
                  }
                  },
                  
               
            ]
        }
    ]
};

  zingchart.render({ 
      id : 'GradTemperature', 
      data : GradTemperature, 
      height: 600, 
      width: 140
  });
}







//////////////////////////////

</script>

</html>