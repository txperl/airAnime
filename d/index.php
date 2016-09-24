<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>InfoDownload -airAnime Online</title>
    <link rel='stylesheet prefetch' href='css/jquery-ui1.11.2.css'>
<style>
* { -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; }

.list-wrap label {
  float:left;
  color:#00BDE8;
}
.search-box {
  float:left;
  clear:left;
  width:70%;
  padding:0.4em;
  font-size:1em;
  color:#555;
}

.list-count {
  float:left;
  text-align:center;
  width:30%;
  padding:0.5em;
  color:#ddd;
}

li {
  transition-property: margin, background-color, border-color;
  transition-duration: .4s, .2s, .2s;
  transition-timing-function: ease-in-out, ease, ease;
  font-size: 14px;
  font-family: 'Microsoft YaHei', Tahoma, Helvetica, Arial;
}

.empty-item {
  transition-property: opacity;
  transition-duration: 0s;
  transition-delay: 0s;
  transition-timing-function: ease;
}

.empty .empty-item {
  transition-property: opacity;
  transition-duration: .2s;
  transition-delay: .3s;
  transition-timing-function: ease;
}

.hiding {
  margin-left:-100%;
  opacity:0.5;
}

.hidden {
  display:none;
}

ul {
  float:left;
  width:100%;
  margin:2em 0;
  padding:0;
  position:relative;
}

ul:before {
  content:'results';
  position:absolute;
  left:-2.8em;
  font-size:3em;
  text-align:right;
  top:1.5em;
  color:#ededed;
  font-weight:bold;
  font-family: 'Maven Pro', sans-serif;
  transform:rotate(-90deg);
}

li {
  float:left;
  clear:left;
  width:100%;
  margin:0.2em 0;
  padding:0.5em 0.8em;
  list-style:none;
  background-color:#f2f2f2;
  border-left:5px solid #003842;
  cursor:pointer;
  color:#333;
  position:relative;
  z-index:2;
}

li:hover {
  background-color:#f9f9f9;
  border-color:#00BDE8;
}

.empty-item {
  background:#fff;
  color:#ddd;
  margin:0.2em 0;
  padding:0.5em 0.8em;
  font-style:italic;
  border:none;
  text-align:center;
  visibility:hidden;
  opacity:0;
  float:left;
  clear:left;
  width:100%;
}

.empty .empty-item {
  opacity:1;
  visibility:visible;
}

body {
  background-color:#fff;
  font-family:'Open Sans', sans-serif;
  margin:0;
  padding:0;
  font-size:1em;
}

a {color:#00BDE8;}

h1 {
  font-size:2.6em;
  margin:0;
  padding-top:1.5em;
  text-align:center;
  font-family: 'Maven Pro', sans-serif;
}
h3 {
  margin:0 0 2em;
  text-align:center;
font-weight:normal;
font-family: georgia, times;
font-style:italic;
  color:#777;
  font-size:1em;
}

.info {
  float:left;
  width:60%;
  margin:2em 20%;
  padding:2em 0;
  background:#f9f9f9;
  border-left:5px solid #003842;
  padding:10px 20px;
}

.list-wrap {
  float:left;
  width:65%;
  margin:2em 20%;
  padding:2em 0;
}

p {
  text-align:left;
  font-size:1em;
}

.cta {
  float:left;
  width:100%;
  text-align:center;
  color:#999;
  font-family:georgia, times;
  font-style:italic;
  margin:2em 0;
}

.cta a {
  font-size:1.5em;
  font-style:normal;
  font-family: 'Maven Pro', sans-serif;
  text-decoration:none;
  line-height:1.5em;
}

.topdeco {
  float:left;
  width:100%;
  height:10px;
  position:fixed;
  z-index:10;
}

.topdeco span {
  float:left;
  width:25%;
  height:100%;
}

.deco span:nth-child(1) {
  background:#FF8220;
}
.deco span:nth-child(2) {
  background:#000;
}
.deco span:nth-child(3) {
  background:#FFA00A;
}
.deco span:nth-child(4) {
  background:#00BDE8;
}

.ddbb{
	display: block;
    float: right;
    font-family: 'Microsoft YaHei', Tahoma, Helvetica, Arial;
    font-size: 10px;
}
</style> 
        <script src="js/prefixfree.min.js"></script>
  </head>
  <body>

<div class="deco topdeco">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  </div>

<?php
  $title = $_SERVER['QUERY_STRING'];
    echo '<h1>'.urldecode($title).'</h1>';
?>

 <section class="list-wrap">

   <label for="search-text">Search the list</label>
  <input type="text" id="search-text" class="search-box">
    <span class="list-count"></span>
    
    
<ul id="list">
<?php
require "../mains.php";
  $title = $_SERVER['QUERY_STRING'];
  $rst=DMHY($title);
  for ($i=0; $i < $rst[5]; $i++) { 
    echo '<a target="_blank" href="'.$rst[1][$i+1].'"><li class="in">'.$rst[0][$i+1].'<div class="ddbb">'.$rst[2][$i+1].' | '.$rst[4][$i+1].'</div></li></a>';
  }
  //<a href="{dmhy_地址}"><li class="in">{dmhy_标题}<div class="ddbb">{dmhy_info}</div></li></a>
?>
  <span class="empty-item">no results</span>
</ul>
   </section>
   
<p class="cta">
  <?php
  $title = $_SERVER['QUERY_STRING'];
    echo 'Page from robsawyer.me<br /> <a id="link" target="_blank" href="http://share.dmhy.org/topics/list?keyword='.$title.'">Detailed info.</a>'
    ?>
</p>
<script src='js/jquery.min2.1.3.js'></script>
<script src='js/jquery-ui.min1.11.2.js'></script>
<script src="js/index.js"></script>
</body>
</html><script> throw new Error(""); //SAE未实名特殊处理