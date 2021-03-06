<?php
    include('header.php');
?>
	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
    
    
    <!-- http://galleria.io//  options: http://galleria.io/docs/options/
    =================================================== -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
    <script src="galleria/galleria-1.2.9.min.js"></script>
    
</head>
<body>



	<!-- Primary Page Layout
	================================================== -->

	<!-- Delete everything in this .container and get started on your own site! -->

	
		<div class="sixteen columns">
            <div class="BannerWrap">
              <div class="BannerBKG">
                <div class="BannerRightCap"></div>
              </div>
               
            </div>
	  </div>
        
        
        
        <div class="sixteen columns">
        	<h3>Contact Us</h3>
            <hr>
        </div>
  		<div class="eight columns">
            <h2>Boscoyo Studio CLL</h2>
            <div>12625 Sullivan Rd<br>
			  <div>City of Central , LA <br>
			  12625 Sullivan Rd<br>
			    Phone: 225.330.2535 <br>
			    E-Mail: <a href="mailto:sales@boscoyostudio.com">sales@boscoyostudio.com </a><br>
		      </div>
              <div>&nbsp;</div>
              <hr class="MobileOnly" />
              
              <div class="Box">
              <iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=central+city+louisiana&amp;ie=UTF8&amp;hq=&amp;hnear=Central+City,+East+Baton+Rouge,+Louisiana&amp;gl=us&amp;t=m&amp;source=embed&amp;ll=30.557383,-91.03735&amp;spn=0.051737,0.072956&amp;z=13&amp;iwloc=A&amp;output=embed"></iframe><br />
              </div>
              <div class="BannerShadow"><img src="images/BannerShadow.png"></div>
              <small><a href="https://maps.google.com/maps?q=central+city+louisiana&amp;ie=UTF8&amp;hq=&amp;hnear=Central+City,+East+Baton+Rouge,+Louisiana&amp;gl=us&amp;t=m&amp;ll=30.584726,-91.028252&amp;spn=0.103446,0.145912&amp;z=12&amp;iwloc=A&amp;source=embed" style="text-align:left">View Larger Map</a></small>
              
              <hr class="MobileOnly" />
        </div>
        <div class="eight columns" style=" display:none;">
            <h4>E-mail us by filling out the form below:</h4>
			<form name="questionary" action="http://76.163.252.54:8080/studio/servlet/psoft.customform.CustomForm" method="post">
                <input type="hidden" name="domain" value="">
                <input type="hidden" name="title" value="Contact Us">
                <input type="hidden" name="esubj" value="Submitted Form Report">
                <input type="hidden" name="name" value="=GghoWXsbVRbLl=b">
                <input type="hidden" name="submit_url" value="onsubmit.html">
                <input type="hidden" name="error_url" value="onerror.html">
                <input type="hidden" name="order" value="8 9 10">
			<script type="text/javascript">
            var BUILDER = '/studio/servlet/psoft.masonry.Builder';
            function validate(frm){
            var i = 0;
            if (frm.field_8.value=="") return error(frm.field_8);
            if (frm.field_9.value=="") return error(frm.field_9);
            if (frm.field_10.value=="") return error(frm.field_10);
            var url = document.URL;
            if (url.indexOf(BUILDER) == -1 && url.charAt(url.length)!='/') {
                url = url.substring(0, url.lastIndexOf('/')+1);
            }
            frm.domain.value=url;
            return true;
            }
            function error(field){ alert('You did not fill all required fields!'); field.focus(); return false; }
            </script>
            
            <table class="contact_form">
            <tbody>
              <tr>
                        <td align="right" valign="top" style="vertical-align:text-top;"><input type="hidden" name="name_8" value="Name:">
                            <font face="Arial" size="2" color="#000000"> Name: </font>
                        </td>
                <td align="left" valign="top"><input type="text" name="field_8" size="35"></td>
              </tr> 
                    <tr>
                        <td align="right" valign="top" style="vertical-align:text-top;"><input type="hidden" name="name_9" value="Email:">
                        <input type="hidden" name="rcpt" value="field_9">
                        <font face="Arial" size="2" color="#000000">E-Mail: </font>
                        </td>
                      <td align="left" valign="top"><input type="text" name="field_9" size="35"></td>
              </tr> 
                    <tr>
                        <td align="right" valign="top" style="vertical-align:text-top;"><input type="hidden" name="name_10" value="Comments:">
            			<font face="Arial" size="2" color="#000000"> Comments: </font>
             			</td>
                      <td align="left" valign="top"><textarea name="field_10" rows="4" cols="40"></textarea></td>
              </tr>
                    
                    <tr>
                        <td align="right" valign="top"  style="vertical-align:text-top;"><font face="Arial" size="2" color="#000000">Confirm form validation code:</font>
            			<br><img src="http://76.163.252.54:8080/studio/servlet/psoft.customform.CustomForm?action=img&amp;warning=Wrong validation code. Please retry.&amp;begcolor=%23FFFF00&amp;endcolor=%230000FF"></td>
                      <td align="left" valign="top"><input type="text" name="formrandom" size="8" maxlength="6"></td>
              </tr>
             
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input onClick="return validate(form)" type="submit" value="Submit">
                            <input type="reset" value="Reset">
                        </td>
                    </tr>
              </tbody></table>
          </form>
                </div>
        
<?php
    include('footer.php');
?>


<!-- End Document
================================================== -->
</body>
</html>