<?php
/** Main index for kobackproducts.ca
 *Purpose:  give contact info on creator
 *          advertise products
 *          background on creator
 *
 * Must: make it look pretty
 */

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="sidebarDesign.css" type="text/css" />
    <!-- add javascript page -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script><script src="navigation.js"></script>
    <title>Main Index</title>
</head>

<main>
    <aside>
        <ul id = "toolsList" class="asideList">
		<li id="home" class="asideAnchor" > Back to Home</li>
           	<li id="devInfo" class="asideAnchor">About the Developer</li>
           	<li id= "resInfo" class="asideAnchor">Researched Material</li>
        </ul>
    </aside>
    <section>
        <input type="checkbox" id="myInput">
        <label for="myInput">
            <span class="bar top"></span>
            <span class="bar middle"></span>
            <span class="bar bottom"></span>
        </label>
	
        <section class="content" id="mainContent"></section>
    	</section>
</main>

<footer><span id='siteseal'><script async type='text/javascript' src='https://seal.godaddy.com/getSeal?sealID=hQfcyySwC3UTNieTzQ2OlN6OXHtQcuMQW5yZB1uTyEeWHHVjhMEfPEmb9JPo'></script></span></footer>
</html>