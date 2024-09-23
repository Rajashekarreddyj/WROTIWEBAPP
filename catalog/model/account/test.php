
<? php 
$page = \Drupal:: request() -> getRequestUri();
$current_path = \Drupal:: service('path.current') -> getPath();
$pathArgs = explode('/', $current_path);
$nid = $pathArgs[2];
if ($nid == '' || $nid == 167 || $nid == 215) {
?>

    <div class="country mobile-only">
      <a id="selectedcountry-mobile" href="javascript:void(0);" class="otherlink"></a>
      <div class="countrychange">
        <p>SELECT YOUR COUNTRY  </p>
        <ul id="lang">
          <li><a name="lang" data-val="sa" class="otherlink" href="javascript:changeCountry('');"><img src="/sites/default/files/smartship/saudi_arabia.svg" /><span>Saudi Arabia</span></a></li>
          <li><a name="lang" data-val="bh" class="otherlink" href="javascript:changeCountry('bh');"><img src="/sites/default/files/smartship/bahrain.svg" /><span>Bahrain</span></a></li>
          <li><a name "lang" data-val="eg" class="otherlink" href="javascript:changeCountry('eg');"><img src="/sites/default/files/smartship/egypt.svg" /><span>Egypt</span></a></li>
        <li><a name "lang" data-val="ae" class="otherlink" href="javascript:changeCountry('ae');"><img src="/sites/default/files/smartship/united-arab-emirates.svg" style="width:36px; height:36px;" /><span>United Arab Emirates</span></a></li>

    </ul>
</div >
</div >
    <header class="fixed-top" id="header">
      <div class="header-container"><a class="logo" href="/"><img alt="" class="img-fluid" src="/sites/default/files/smartship/Logo.svg" /></a>
        <div class="rightSide">
          <nav class="nav-menu d-none d-lg-block">
            <div class="mobile-nav-header">
              <div class="mobile-logo"><a href="/" class="logo"><img src="/sites/default/files/smartship/Logo.svg" alt="" class="img-fluid"></a></div>

            </div>
            <ul>
              <li <?php if($nid ==''){echo 'class="active"'; } else{"noactive"; } ?>><a href="/">Home</a></li>
            <li <?php if($nid ==167){echo 'class="active"'; } else{"noactive"; } ?>><a href="/about-us/">About Us</a></li>
          <li <?php if($nid ==215){echo 'class="active"'; } else{"noactive"; } ?>><a href="/how-it-works/">How it Works</a></li>
        <li <?php if($nid ==214){echo 'class="active"'; } else{"noactive"; } ?>><a href="/track-shipment/">Track shipment</a></li>
      <!-- <li><a href="Newsroom.html">Newsroom</a></li> -->
      <li <?php if($nid ==212){echo 'class="active"'; } else{"noactive"; } ?>><a href="/contact-us/">Contact Us</a></li>
</ul >
    <a class="arbic-ln change mobile-only" href="/ar/">عربي</a>
</nav >


    <div class="sidelinks"><a class="arbic-ln change" href="/ar<?php echo $page; ?>">عربي</a> |

      <div class="get-started-btn"><a class="login" href="https://app.smartship.com/User/Login" target="_blank"><span>Login</span></a> / <a class="register" href="https://app.smartship.com/User/ValidateUser" target="_blank"><span>Register</span></a></div>

      <div class="country">
        <a id="selectedcountry" class="otherlink" href="javascript:;"></a>
        <div class="countrychange">
          <p>SELECT YOUR COUNTRY </p>

          <ul id="lang">
            <li><a name="lang" data-val="" class="otherlink" href="javascript:changeCountry('');"><img src="/sites/default/files/smartship/saudi_arabia.svg" /><span>Saudi Arabia</span></a></li>
            <li><a name="lang" data-val="bh" class="otherlink" href="javascript:changeCountry('bh');"><img src="/sites/default/files/smartship/bahrain.svg" /><span>Bahrain</span></a></li>
            <li><a name "lang" data-val="eg" class="otherlink" href="javascript:changeCountry('eg');"><img src="/sites/default/files/smartship/egypt.svg" /><span>Egypt</span></a></li>
          <li><a name "lang" data-val="ae" class="otherlink" href="javascript:changeCountry('ae');"><img src="/sites/default/files/smartship/united-arab-emirates.svg" style="width:36px; height:36px;" /><span>United Arab Emirates</span></a></li>

      </ul>
    </div>
</div >
</div >
</div >
</div >
</header >
<? php } else {  ?>
  <div class="otherpage">
    <div class="country mobile-only">
      <a id="selectedcountry-mobile" href="javascript:void(0);" class="otherlink"></a>
      <div class="countrychange">
        <p>SELECT YOUR COUNTRY </p>
        <ul id="lang">
          <li><a name="lang" data-val="" class="otherlink" href="javascript:changeCountry('');"><img src="/sites/default/files/smartship/saudi_arabia.svg" /><span>Saudi Arabia</span></a></li>
          <li><a name="lang" data-val="bh" class="otherlink" href="javascript:changeCountry('bh');"><img src="/sites/default/files/smartship/bahrain.svg" /><span>Bahrain</span></a></li>
          <li><a name "lang" data-val="eg" class="otherlink" href="javascript:changeCountry('eg');"><img src="/sites/default/files/smartship/egypt.svg" /><span>Egypt</span></a></li>
        <li><a name "lang" data-val="ae" class="otherlink" href="javascript:changeCountry('ae');"><img src="/sites/default/files/smartship/united-arab-emirates.svg" style="width:36px; height:36px;" /><span>United Arab Emirates</span></a></li>

    </ul>
  </div>
</div >
    <header class="fixed-top" id="header">
      <div class="header-container"><a class="logo" href="/"><img alt="" class="img-fluid" src="/sites/default/files/smartship/Logo.svg" /></a>
        <div class="rightSide">
          <nav class="nav-menu d-none d-lg-block">
            <div class="mobile-nav-header">
              <div class="mobile-logo"><a href="/" class="logo"><img src="/sites/default/files/smartship/Logo.svg" alt="" class="img-fluid"></a></div>

            </div>
            <ul>
              <li <?php if($nid ==''){echo 'class="active"'; } else{"noactive"; } ?>><a href="/">Home</a></li>
            <li <?php if($nid ==167){echo 'class="active"'; } else{"noactive"; } ?>><a href="/about-us/">About Us</a></li>
          <li <?php if($nid ==215){echo 'class="active"'; } else{"noactive"; } ?>><a href="/how-it-works/">How it Works</a></li>
        <li <?php if($nid ==214){echo 'class="active"'; } else{"noactive"; } ?>><a href="/track-shipment/">Track shipment</a></li>
      <!-- <li><a href="Newsroom.html">Newsroom</a></li> -->
      <li <?php if($nid ==212){echo 'class="active"'; } else{"noactive"; } ?>><a href="/contact-us/">Contact Us</a></li>
</ul >
    <a class="arbic-ln change mobile-only" href="/ar/">عربي</a>
</nav >
    <div class="sidelinks"><a class="arbic-ln change" href="/ar<?php echo $page; ?>">عربي</a> |

      <div class="get-started-btn"><a class="login" href="https://app.smartship.com/User/Login" target="_blank"><span>Login</span></a> / <a class="register" href="https://app.smartship.com/User/ValidateUser" target="_blank"><span>Register</span></a></div>
      <div class="country">
        <a id="selectedcountry1" class="otherlink" href="javascript:;"></a>
        <div class="countrychange">
          <p>SELECT YOUR COUNTRY </p>

          <ul id="lang">
            <li><a name="lang" data-val="" class="otherlink" href="javascript:changeCountry('');"><img src="/sites/default/files/smartship/saudi_arabia.svg" /><span>Saudi Arabia</span></a></li>
            <li><a name="lang" data-val="bh" class="otherlink" href="javascript:changeCountry('bh');"><img src="/sites/default/files/smartship/bahrain.svg" /><span>Bahrain</span></a></li>
            <li><a name "lang" data-val="eg" class="otherlink" href="javascript:changeCountry('eg');"><img src="/sites/default/files/smartship/egypt.svg" /><span>Egypt</span></a></li>
          <li><a name "lang" data-val="ae" class="otherlink" href="javascript:changeCountry('ae');"><img src="/sites/default/files/smartship/united-arab-emirates.svg" style="width:36px; height:36px;" /><span>United Arab Emirates</span></a></li>

      </ul>
    </div>
</div >
</div >
</div >
</div >
</header >
</div >
<? php  } ?>
<script>
  function processCountry(countrycode) { 

    
   if(countrycode == "eg")
    {
      jQuery('#selectedcountry').html('<span><img src="/sites/default/files/smartship/egypt.svg" /></span>');
    jQuery('#selectedcountry1').html('<span><img src="/sites/default/files/smartship/egypt.svg" /></span>');
    jQuery('#selectedcountry-mobile').html('<span><img src="/sites/default/files/smartship/egypt.svg" /></span>');

   }
    else if(countrycode == "ae")
    {
      jQuery('#selectedcountry').html('<span><img src="/sites/default/files/smartship/united-arab-emirates.svg" /></span>');
    jQuery('#selectedcountry1').html('<span><img src="/sites/default/files/smartship/united-arab-emirates.svg" /></span>');
    jQuery('#selectedcountry-mobile').html('<span><img src="/sites/default/files/smartship/united-arab-emirates.svg" /></span>');
   }
    else if(countrycode == "bh")
    {
      jQuery('#selectedcountry').html('<span><img src="/sites/default/files/smartship/bahrain.svg" /></span>');
    jQuery('#selectedcountry1').html('<span><img src="/sites/default/files/smartship/bahrain.svg" /></span>');
    jQuery('#selectedcountry-mobile').html('<span><img src="/sites/default/files/smartship/bahrain.svg" /></span>');
   }
    else
    {
      jQuery('#selectedcountry').html('<span><img src="/sites/default/files/smartship/saudi_arabia.svg" /></span>');
    jQuery('#selectedcountry1').html('<span><img src="/sites/default/files/smartship/saudi_arabia.svg" /></span>');
    jQuery('#selectedcountry-mobile').html('<span><img src="/sites/default/files/smartship/saudi_arabia.svg" /></span>');

 }
}
</script>
  <script>
    function changeCountry(path){
        
  if(path == 'eg' || path == 'ae' || path == 'bh' || path == 'sa'){
  
   var countrycode = localStorage.getItem("country");

    if(path != countrycode)  {
     
      localStorage.setItem("country",path);
    processCountry(path); 
     setTimeout(() => {
      window.location.href = "http://uat.smartship.com/" + path == 'sa'?'':path;
}, 3000);
    
   }else{


      setTimeout(() => {
        processCountry(path);
      }, 3000);
   }
  }

}
    var c_code = "";
    window.onload = function() { 
    var countrycode = localStorage.getItem("country");
    alert(countrycode)
    if(countrycode == null){
      $.get("https://ipinfo.io", function (response) {
        const cCodes = ["sa", "bh", "eg", "ae"];
        c_code = response.country;
        if(!cCodes.includes(c_code.toLowerCase())){
            c_code = 'sa';
        }
        changeCountry(c_code.toLowerCase());

      }, "jsonp");
}else{

    var url =  "http://uat.smartship.com/" + countrycode == 'sa'?'':countrycode;
    
   processCountry(countrycode)
}




    jQuery('#simplenews-subscriptions-block-0414a055-65c8-4649-a97d-c29dbe398465').submit(function(e){
      e.preventDefault();
    if(!validateSubscribeForm())
    return false;

    var settings = {
      "url": "/subscribe-news-letter/",
    "method": "POST",
    "timeout": 0,
    "data": jQuery(this).serialize(),
           };

    jQuery.ajax(settings).done(function(data){
      jQuery("#simplenews-subscriptions-block-0414a055-65c8-4649-a97d-c29dbe398465").trigger("reset");
    jQuery('#news-message').fadeIn().html('Thank you for your subscription.');
    setTimeout(function() {
      jQuery('#news-message').fadeOut("slow");
        }, 6000 );
     });
 });


    jQuery("#lang li").click(function() { 
    var countrycode = jQuery(this).find('a').attr("data-val");
    localStorage.setItem("country",countrycode);
    processCountry(countrycode); 
});

    jQuery("#edit-mail-0-value").on("change",function() {
      console.log("edit-mail-0-value change");
    jQuery('#news-message').html('');
});

    function validateSubscribeForm()
    {
  var isValid = false;
    var userinput = jQuery("#edit-mail-0-value").val();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2, 4}\b$/i

    if(!pattern.test(userinput))
    {
      jQuery('#news-message').css({ "color": "red", "display": "block" });
    jQuery('#news-message').html('Please enter valid email id');
    return false;
  }
    else
    {
      jQuery('#news-message').html('');
  }

    return true;
}

  

    function validateContactForm()
    {
  var isValid = false;

    var name = jQuery("#edit-name").val();
    if (!name)
    {
      jQuery('#namevalidation').html('Please enter name');
    return false;
  }
    else
    {
      jQuery('#namevalidation').html('');
  }
    //var re = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    //var emailFormat = re.test($("#email").val());  
    var userinput = jQuery("#edit-mail").val();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2, 4}\b$/i

    if(!pattern.test(userinput))
    {
      jQuery('#emailvalidation').html('Please enter a valid email id');
    return false;
  }
    else
    {
      jQuery('#emailvalidation').html('');
  }

    var num = jQuery("#edit-field-phone-number-0-value").val();

    if (!num)
    {
      jQuery('#phonevalidation').html('Please enter phone number');
    return false;
  }
    else
    {
      jQuery('#phonevalidation').html('');
  }

    var regex = /^[0-9\s]*$/;
    isValid = regex.test(jQuery("#edit-field-phone-number-0-value").val());
    if(!isValid)
    {
      jQuery('#phonevalidation').html('Please enter a valid phone number');
    return false;
  }
    else
    {
      jQuery('#phonevalidation').html('');
  }

    var msg = jQuery("#edit-message-0-value").val();
    if (!msg)
    {
      jQuery('#msgvalidation').html('Please enter message');
    return false;
  }
    else
    {
      jQuery('#msgvalidation').html('');
  }

    return true;
}


    jQuery("#edit-name").on("change",function() {
      jQuery('#namevalidation').html('');
});

    jQuery("#edit-mail").on("change",function() {
      jQuery('#emailvalidation').html('');
});
    jQuery("#edit-field-phone-number-0-value").on("change",function() {
      jQuery('#phonevalidation').html('');
});
    jQuery("#edit-message-0-value").on("change",function() {
      jQuery('#msgvalidation').html('');
});

    jQuery('#contact-message-feedback-form').submit(function(e){
      e.preventDefault();
    if(!validateContactForm())
    {
      return false;
   }
    jQuery('#loading').html('Processing...');

    var values, index;

    // Get the parameters as an array
    values = jQuery(this).serializeArray();

    for (index = 0; index < values.length; ++index) {
    if (values[index].name === "name") {
      values[index].value = "<br/>Name:" + values[index].value + "<br/>" ;
       
    }


    if (values[index].name === "field_city[0][value]") {
      values[index].value = "<br/>City:" + values[index].value ;
  
    }
    if (values[index].name === "field_phone_number[0][value]") {
      values[index].value = "<br/>Phone:" + values[index].value ;
  
    }
    if (values[index].name === "message[0][value]") {
      values[index].value = "<br/>Message:" + values[index].value ;
  
    }
}

    values = jQuery.param(values);
    var settings = {
      "url": "/contact/",
    "method": "POST",
    "timeout": 0,
    "data": values,
         };

    jQuery.ajax(settings).done(function(data){
      jQuery("#contact-message-feedback-form").trigger("reset");
    jQuery('#loading').html('');
    jQuery('#sent-message').fadeIn().html('Your message has been sent , Thank you.');

    setTimeout(function() {
      jQuery('#sent-message').fadeOut("slow");
        }, 2000 );
         })
    .fail(function() {
      jQuery('#loading').html('');
    jQuery('#error-message').fadeIn().html('Error in form submission.');
    setTimeout(function() {
      jQuery('#error-message').fadeOut("slow");
          }, 2000 );
        });
      });


    var results = new RegExp('[\?&]' + 'awbnumber' + '=([^&#]*)').exec(window.location.href);
    var awbgetnumber='';
    if(results != null && typeof results[1] != 'undefined' && results[1]){
      awbgetnumber = results[1];
    jQuery("#awbinput").val(awbgetnumber);
    var ambinputval =  jQuery("#awbinput").val();
    if(!validateForm())
    {
      jQuery('#awbvalidation').html('#' + ambinputval + ' This tracking number is not found, please check again later or contact the sender');
    return false;
                }
    processForm();
     }

    jQuery('#userForm').submit(function(e){
      e.preventDefault();
    var ambinputval =  jQuery("#awbinput").val();
    if(!validateForm())
    {
      jQuery('#awbvalidation').html('#' + ambinputval + ' This tracking number is not found, please check again later or contact the sender');
    jQuery("#tracksummery").hide();
    jQuery("#trackactivity").hide();
    return false;
                }
    clearResults();
    processForm();
    // to prevent refreshing the whole page page
    return false;
      });
    function clearResults() {
      jQuery('#awbvalidation').html('');
    jQuery("#activity").html('No Data');
    jQuery('#awbnumber').html('');
    jQuery('#consigndate').html('');
    jQuery('#edd').html('');
    jQuery('#awbstatus').html('');
    jQuery('#consineeName').html('');
    jQuery('#consineeCity').html('');

    jQuery('#shipperName').html('');
    jQuery('#shipperCity').html('');
    jQuery('#from').html('');
    jQuery('#to').html('');

    jQuery('#weight').html('');
    jQuery('#parcels').html('');
    jQuery('#step1').removeClass("active");
    jQuery('#step2').removeClass("active");
    jQuery('#step3').removeClass("active");
    jQuery('#step4').removeClass("active");
    jQuery('#step5').removeClass("active");
    jQuery('#step6').removeClass("active");
    jQuery('.shipping-progress .progress').removeClass('active');
}
    function validateForm() { 
  var awbinputnumber = jQuery('#awbinput').val();
    var strNum = awbinputnumber.toString();
    strNum = strNum.replace(/\s/g, '');

    var sum = 0;
    if(strNum.length != 12)
    {
    return false;
  }
    var firstLetters = strNum.substring(0, 1);
    if(firstLetters != "2")
    {
    return false;
  }

    var variants = [5, 1, 7, 5, 1, 7, 5, 1, 7, 5, 1];
    var totalVal = 0;
    for(var i = 0; i < 11; i++ ){
      totalVal += (variants[i] * parseInt(strNum[i]));
    }

    var remainder = totalVal % 11;
    var flag = false;
    if (remainder == 10)
    remainder = 0;
    if (remainder == parseInt(strNum[11])) {
      flag = true;
  } else {
      flag = false;
  }

    return flag;
}

    function processForm() {

var awbinputnumber = jQuery('#awbinput').val();
    awbinputnumber = awbinputnumber.replace(/\s/g, '');
    //var postfields = "[\"" + awbinputnumber + "\"]";
    postfields = [];
    postfields[0] = awbinputnumber;
    var settings = {
      "url": "https://services.smartship.com/api/Order/GetShipmentsByAWB/3fa85f64-5717-4562-b3fc-2c963f66afa6",
    "method": "POST",
    "timeout": 0,
    "headers": {
      "Content-Type": "application/json"
  },
    "data": JSON.stringify(postfields),
};

    jQuery.ajax(settings).done(function(data){ // if getting done then call.
      console.log(data);
    jQuery("#tracksummery").removeAttr('hidden');
    jQuery("#tracksummery").show();
    var summaryObj = data; //JSON.parse(data);
    // show the response
    if( summaryObj.entity == null || typeof summaryObj.entity[0] == 'undefined') return false;

    const options = {year: 'numeric', month: 'long', day: 'numeric' };
    if (summaryObj.entity[0].shipmentDate != '0001-01-01T00:00:00')
    {
             var consigndate = new Date(summaryObj.entity[0].shipmentDate);
    jQuery('#consigndate').html(consigndate.toLocaleDateString('en-US', options));
    }
    else
    {
      jQuery('#consigndate').html('');
   }
    //if (summaryObj.entity[0].edd && summaryObj.entity[0].edd != '0001-01-01T00:00:00' && jQuery('#edd').html() == '')
    //{
      //         var eddate = new Date(summaryObj.entity[0].edd); 
      //        jQuery('#edd').html(eddate.toLocaleDateString('en-US', options));
      //      jQuery('#edd').parent().css('opacity', '1');
      //}
      //else
      //{
      //   jQuery('#edd').html('');
      //  jQuery('#edd').parent().css('opacity', '0');
      //} 
      jQuery('#awbnumber').html('#' + summaryObj.entity[0].awbNumber);

    var statusVal = summaryObj.entity[0].status;
    var statusMap = {
      "O": '#step0',
    "ADV": '#step0',
    "Data": '#step0',
    "PU": '#step1',
    "P": '#step2',
    "I": '#step2',
    "PRC": '#step2',
    "DEX41": '#step2',
    "DF": '#step2',
    "SOP": '#step2',
    "ST68": '#step2',
    "R": '#step2',
    "OD": '#step3',
    "WC": '#step3',
    "AF": '#step3',
    "W": '#step3',
    "AR": '#step3',
    "SMS": '#step3',
    "ST41": '#step3',
    "ST44": '#step3',
    "TP": '#step3',
    "DEX17": '#step3',
    "X": '#step3',
    "D": '#step4',
    "DL": '#step4',
    "DEX09": '#step4',
    "OK": '#step4'
    };

    if( !statusVal || statusVal.length === 0)
    {
      jQuery('.shippingprogress').hide();
    }
    else
    {
      jQuery('.shippingprogress').show();
    if(statusMap[String(statusVal)])
    jQuery(statusMap[String(statusVal)]).addClass("active");
    else
    jQuery('#step2').addClass("active");
    if(jQuery('.shipment-progress .progress div.active').length) {
      jQuery('.shipment-progress .progress').removeClass('inactive');
       } else {
      jQuery('.shipment-progress .progress').addClass('inactive');
       }
     }
    var statusString = statusMap[String(statusVal)];
    var statusName = summaryObj.entity[0].statusText;
    if(statusString == "#step1")
    statusName = "Picked Up";
    else if(statusString == "#step2" || !statusString)
    statusName = "In Transit";
    else if(statusString == "#step3")
    statusName = "Out for Delivery";
    else if(statusString == "#step4")
    statusName = "Delivered";

    jQuery('#awbstatus').html(statusName);

    var consineeData = '';
    if( summaryObj.entity[0].consineeCityName !=  null ){
      consineeData = summaryObj.entity[0].consineeCityName;
    }
    if( summaryObj.entity[0].consineeCountry !=  null ){
      consineeData = consineeData + ',' + summaryObj.entity[0].consineeCountry;
    }

    jQuery('#consineeName').html(summaryObj.entity[0].consineeName);
    jQuery('#consineeCity').html(consineeData);

    jQuery('#shipperName').html(summaryObj.entity[0].shipperName);
    var shipData = '';
    if( summaryObj.entity[0].shipperCityName !=  null ){
      shipData = summaryObj.entity[0].shipperCityName;
    }
    if( summaryObj.entity[0].shipperCountry !=  null ){
      shipData = shipData + ',' + summaryObj.entity[0].shipperCountry;
    }


    jQuery('#shipperCity').html(shipData);

    jQuery('#to').html(summaryObj.entity[0].consineeName+'<br>'+consineeData);
      jQuery('#from').html(summaryObj.entity[0].shipperName+'<br>'+ shipData);
        if(summaryObj.entity[0].weight)
        {
        var weightStr = summaryObj.entity[0].weight + " KG";
        jQuery('#weight').html(weightStr);
    }
        jQuery('#parcels').html(summaryObj.entity[0].parcels);

    })
        .fail(function() { // if fail then getting message

          // just in case posting your form failed

        });

        var urlTrack = "https://services.smartship.com/api/Order/TrackAWB/3fa85f64-5717-4562-b3fc-2c963f66afa6/"+awbinputnumber;
        var settings = {
          "url": urlTrack,
        "method": "GET",
        "timeout": 0,
      };
        jQuery.ajax(settings)
        .done(function(data){console.log(data);
        const options = {year: 'numeric', month: 'long', day: 'numeric' };
        var shipmentData = data.entity.shipmentDetails;
        if(shipmentData.edd && jQuery('#edd').html() == '') {
var eddate = new Date(shipmentData.edd);
        jQuery('#edd').html(eddate.toLocaleDateString('en-US', options));
        jQuery('#edd').parent().css('opacity', '1');
} else
        {
          jQuery('#edd').html('');
        jQuery('#edd').parent().css('opacity', '0');
   }

        jQuery("#trackactivity").removeAttr('hidden');
        jQuery("#trackactivity").show();
        var trackObj = data; //JSON.parse(data);

        var trackArray = { }

        for(var track in trackObj.entity.trackingDetails) { 
      var trackData = trackObj.entity.trackingDetails[track];
        var date = new Date(trackData.eventTime);
        var dateindex = date.getUTCDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();

        trackArray[String(dateindex)] = [];
      }

        for(var track in trackObj.entity.trackingDetails) { 
      var trackData = trackObj.entity.trackingDetails[track];
        var date = new Date(trackData.eventTime);
        var dateindex = date.getUTCDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        var trackLoc ='';
        if( trackData.countryCode !=  null && trackData.countryCode !=  '' ){
          trackLoc = trackData.office + " " + trackData.countryCode ;
      }
        let step = {
          "desc": trackData.eventDesc,
        "trackLoc": trackLoc,
        "daytime": date.toLocaleString('en-US', {timeZone: 'UTC',
        weekday: 'long',
        hour: '2-digit',
        minute: '2-digit'
                    })
      }
        trackArray[String(dateindex)].push(step);
      }
        var datesKeys = Object.keys(trackArray);
        jQuery("#activity").html('');
        var activityHtml = '';
       //jQuery("#singletrack").append( "<div id=\"activity\">");
        var month = new Array();
        month[1] = "Jan";
        month[2] = "Feb";
        month[3] = "Mar";
        month[4] = "April";
        month[5] = "May";
        month[6] = "June";
        month[7] = "July";
        month[8] = "Aug";
        month[9] = "Sep";
        month[10] = "Oct";
        month[11] = "Nov";
        month[12] = "Dec";


        for (var i = 0; i < datesKeys.length; i++) {
        var arrVars = datesKeys[i].split("-");
        var n = month[arrVars[1]];
        var datek = arrVars[0]+'-'+n+'-'+arrVars[2];
        activityHtml = activityHtml + "<div class=\"day-activity\"><div class=\"activity-date\">" + datek;
        activityHtml = activityHtml + "<\/div>";
        var daysteps = trackArray[datesKeys[i]];
          daysteps.forEach(aStep => {
          activityHtml = activityHtml + "<div class=\"step\"><div class=\"step-bar\"><\/div><div class=\"step-bullet\"><\/div>";
        activityHtml = activityHtml + "<div class=\"step-description\"><div class=\"d-flex justify-content-between  w-100\">";
        activityHtml = activityHtml + "<div id=\"day-time\">" + aStep.daytime;
        activityHtml = activityHtml + "<\/div><\/div><p>" + aStep.desc + "<\/p>";
          if(aStep.trackLoc != null && aStep.trackLoc != '')
          {
            activityHtml = activityHtml + "<div class=\"location\">" + aStep.trackLoc + "<\/div>";
          }

          activityHtml = activityHtml + "<\/div>";
          activityHtml = activityHtml + "<\/div>";
             });
          activityHtml = activityHtml + "<\/div>";

      }
          jQuery("#activity").html(activityHtml);
    })
          .fail(function() {

            alert("Getting data failed.");

    });
  }

};
        </script>