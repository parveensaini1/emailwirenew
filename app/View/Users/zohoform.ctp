<style> #zohoSupportWebToCase textarea,#zohoSupportWebToCase input[type='text'],#zohoSupportWebToCase select,.wb_common{width:280px}
#zohoSupportWebToCase td{padding:11px 5px}
#zohoSupportWebToCase textarea,#zohoSupportWebToCase input[type='text'],#zohoSupportWebToCase select{border:1px solid #ddd;padding:3px 5px;border-radius:3px}
#zohoSupportWebToCase select{box-sizing:unset}
#zohoSupportWebToCase .wb_selectDate{width:auto}
#zohoSupportWebToCase input.wb_cusInput{width:108px}
.wb_FtCon{display:flex;align-items:center;justify-content:flex-end;margin-top:15px;padding-left:10px}
.wb_logoCon{display:flex;margin-left:5px}
.wb_logo{max-width:16px;max-height:16px}
.zsFormClass{background-color:#FFF;width:600px}
.zsFontClass{color:#000;font-family:Arial;font-size:13px}
.manfieldbdr{border-left:1px solid #ff6448!important}
.hleft{text-align:left}
input[type=file]::-webkit-file-upload-button{cursor:pointer}
.wtcsepcode{margin:0 15px;color:#aaa;float:left}
.wtccloudattach{float:left;color:#00a3fe!important;cursor:pointer;text-decoration:none!important}
.wtccloudattach:hover{text-decoration:none!important}
.wtcuploadinput{cursor:pointer;float:left;width:62px;margin-top:-20px;opacity:0;clear:both}
.wtcuploadfile{float:left;color:#00a3fe}
.filenamecls{margin-right:15px;float:left;margin-top:5px}
.clboth{clear:both}
#zsFileBrowseAttachments{clear:both;margin:5px 0 10px}
.zsFontClass{vertical-align:top}
#tooltip-zc{font:normal 12px Arial,Helvetica,sans-serif;line-height:18px;position:absolute;padding:8px;margin:20px 0 0;background:#fff;border:1px solid #528dd1;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;color:#eee;-webkit-box-shadow:5px 5px 20px rgba(0,0,0,0.2);-moz-box-shadow:5px 5px 20px rgba(0,0,0,0.2);z-index:10000;color:#777}
.wtcmanfield{color:red;font-size:16px;position:relative;top:2px;left:1px}
#zsCloudAttachmentIframe{width:100%;height:100%;z-index:99999!important;position:fixed;left:0;top:0;border-style:none;display:none;background-color:#fff}
.wtchelpinfo{background-position:-246px -485px;width:15px;height:15px;display:inline-block;position:relative;top:2px;background-image:url(https://css.zohostatic.com/support/2442693/images/zs-mpro.png)}
.zsMaxSizeMessage{font-size:13px}
</style>
<script src='https://js.zohostatic.in/support/app/js/jqueryandencoder.ffa5afd5124fbedceea9.js'></script><script>function trimBoth(str){return jQuery.trim(str);}function setAllDependancyFieldsMapping(){var mapDependancyLabels = getMapDependenySelectValues(jQuery("[id='property(module)']").val(), "JSON_MAP_DEP_LABELS");if(mapDependancyLabels){for(var i = 0; i < mapDependancyLabels.length; i++){var label = mapDependancyLabels[i];var obj = document.forms['zsWebToCase_14278000000048556'][label];if(obj){setDependent(obj, true);}}}}function getMapDependenySelectValues(module, key){var dependencyObj = jQuery.parseJSON( jQuery("[id='dependent_field_values_" + module + "']").val() );if(dependencyObj == undefined){return dependencyObj;}return dependencyObj[key];}function setDependent(obj, isload){var name = obj.id || (obj[0] && obj[0].id) || "";var module = jQuery("[id='property(module)']").val();var val = "";var myObject = getMapDependenySelectValues(module, "JSON_VALUES");if(myObject !=undefined){val = myObject[name];}var mySelObject = getMapDependenySelectValues(module, "JSON_SELECT_VALUES");if(val != null && val != "" && val != "null" && mySelObject){var fields = val;for(var i in fields){if (fields.hasOwnProperty(i)){var isDependent = false;var label = i;var values = fields[i];if(label.indexOf(")") > -1){label = label.replace(/\)/g, '_____');}if(label.indexOf("(") > -1){label = label.replace(/\(/g, '____');}if(label.indexOf(".") > -1){label = label.replace(/\./g, '___');}var depObj = document.forms['zsWebToCase_14278000000048556'][label];if(depObj && depObj.options){var mapValues = "";var selected_val = depObj.value;var depLen = depObj.options.length-1;for (var n = depLen; n >= 0; n--) {if (depObj.options[n].selected){if(mapValues == ""){mapValues = depObj.options[n].value;}else{mapValues = mapValues + ";;;"+depObj.options[n].value;}}}depObj.value = "";var selectValues = mySelObject[label];for(var k in values){var rat = k;if(rat == "-None-"){rat = "";}var parentValues = mySelObject[name];if(rat == trimBoth(obj.value)){isDependent = true;depObj.length = 0;var depvalues = values[k];var depLen = depvalues.length - 1;for(var j = 0; j <= depLen; j++){var optionElement = document.createElement("OPTION");var displayValue = depvalues[j];var actualValue = displayValue;if(actualValue == "-None-"){optionElement.value = "";displayValue = "-None-";}else{optionElement.value = actualValue;}optionElement.text = displayValue;if(mapValues != undefined){var mapValue = mapValues.split(";;;");var len = mapValue.length;for(var p = 0; p < len; p++){if(actualValue == mapValue[p]){optionElement.selected = true;}}}depObj.options.add(optionElement);}}}if(!isDependent){depObj.length = 0;var len = selectValues.length;for(var j = 0; j < len; j++){var actualValue = selectValues[j];var optionElement = document.createElement("OPTION");if(actualValue == "-None-"){optionElement.value = "";}else{optionElement.value = selectValues[j];}optionElement.text = selectValues[j];depObj.options.add(optionElement);}depObj.value =  selected_val;}if(!isload){setDependent(depObj,false);}var jdepObj = jQuery(depObj);if(jdepObj.hasClass('select2-offscreen')){jdepObj.select2("val", jdepObj.val());}}}}}}var zctt = function(){var tt, mw = 400, top = 10, left = 0, doctt = document;var ieb = doctt.all ? true : false;return{showtt: function(cont, wid){if(tt == null){tt = doctt.createElement('div');tt.setAttribute('id', 'tooltip-zc');doctt.body.appendChild(tt);doctt.onmousemove = this.setpos;doctt.onclick = this.hidett;}tt.style.display = 'block';tt.innerHTML = cont;tt.style.width = wid ? wid + 'px' : 'auto';if(!wid && ieb){tt.style.width = tt.offsetWidth;}if(tt.offsetWidth > mw){tt.style.width = mw + 'px'}h = parseInt(tt.offsetHeight) + top;w = parseInt(tt.offsetWidth) + left;},hidett: function(){tt.style.display = 'none';},setpos: function(e){var u = ieb ? event.clientY + doctt.body.scrollTop : e.pageY;var l = ieb ? event.clientX + doctt.body.scrollLeft : e.pageX;var cw = doctt.body.clientWidth;var ch = doctt.body.clientHeight;if(l < 0){tt.style.left = left + 'px';tt.style.right = '';}else if((l+w+left) > cw){tt.style.left = '';tt.style.right = ((cw-l) + left) + 'px';}else{tt.style.right = '';tt.style.left = (l + left) + 'px';}if(u < 0){tt.style.top = top + 'px';tt.style.bottom = '';}else if((u + h + left) > ch){tt.style.top = '';tt.style.bottom = ((ch - u) + top) + 'px';}else{tt.style.bottom = '';tt.style.top = (u + top) + 'px';}}};}();var zsWebFormMandatoryFields = new Array("First Name","Contact Name","Email","Phone","Subject","Classification","Description");var zsFieldsDisplayLabelArray = new Array("First Name","Last Name","Email","Phone","Subject","Classifications","Description");function zsValidateMandatoryFields(){var name = '';var email = '';var isError = 0;for(var index = 0; index < zsWebFormMandatoryFields.length; index++){isError = 0;var fieldObject = document.forms['zsWebToCase_14278000000048556'][zsWebFormMandatoryFields[index]];if(fieldObject){if(((fieldObject.value).replace(/^\s+|\s+$/g, '')).length == 0){alert(zsFieldsDisplayLabelArray[index] +' cannot be empty ');fieldObject.focus();isError = 1;return false;}else{if(fieldObject.name == 'Email'){if(!fieldObject.value.match(/^([\w_][\w\-_.+\'&]*)@(?=.{4,256}$)(([\w]+)([\-_]*[\w])*[\.])+[a-zA-Z]{2,22}$/)){isError = 1;alert('Enter a valid email-Id');fieldObject.focus();return false;}}}if(fieldObject.nodeName == 'SELECT'){if(fieldObject.options[fieldObject.selectedIndex].value == '-None-'){alert(zsFieldsDisplayLabelArray[index] +' cannot be none');fieldObject.focus();isError = 1;return false;}}if(fieldObject.type == 'checkbox'){if (fieldObject.checked == false){alert('Please accept '+zsFieldsDisplayLabelArray[index]);fieldObject.focus();isError = 1;return false;}}}}if(isError == 0){document.getElementById('zsSubmitButton_14278000000048556').setAttribute('disabled', 'disabled');}}document.onreadystatechange = function(){if(window.zsRegenerateCaptcha){zsRegenerateCaptcha();}setAllDependancyFieldsMapping();document.getElementById('zsSubmitButton_14278000000048556').removeAttribute('disabled');};function zsResetWebForm(webFormId){document.forms['zsWebToCase_'+webFormId].reset();document.getElementById('zsSubmitButton_14278000000048556').removeAttribute('disabled');setAllDependancyFieldsMapping();} </script>

   <div id='zohoSupportWebToCase'>
      <form name='zsWebToCase_14278000000048556' id='zsWebToCase_14278000000048556' action='https://desk.zoho.in/support/WebToCase' method='POST' onSubmit='return zsValidateMandatoryFields()' enctype='multipart/form-data'>
         <input type='hidden' name='xnQsjsdp' value='IBT5DKmvqJxtaux80sJhoQ$$'/>
		 <input type='hidden' name='xmIwtLD' value='BCIYPbNRFszAeMeqSncYyzD6URiqDetd'/>
		 <input type='hidden' name='xJdfEaS' value=''/>
		 <input type='hidden' name='actionType' value='Q2FzZXM='/>
		 <input type="hidden" id="property(module)" value="Cases"/>
		 <input type="hidden" id="dependent_field_values_Cases" value="&#x7b;&quot;JSON_VALUES&quot;&#x3a;&#x7b;&#x7d;,&quot;JSON_SELECT_VALUES&quot;&#x3a;&#x7b;&#x7d;,&quot;JSON_MAP_DEP_LABELS&quot;&#x3a;&#x5b;&#x5d;&#x7d;"/>
		 <input type='hidden' name='returnURL' value='http://netleon.in/email_wire/success'/>
			
			<!-- <h2>Ticket Form</h2> -->
			<div class="row support">
			<div class="col-md-6 col-sm-12">
				<div class="form-group">
					<label>First Name</label>
					<input type='text' maxlength='120' name='First Name' value='' class='form-control'/>
				</div>
			</div>	
			<div class="col-md-6 col-sm-12">
				<div class="form-group">
					<label>Last Name</label>
					<input type='text' maxlength='120' name='Contact Name' class='form-control'/>
				</div>
			</div>
			<div class="col-md-6 col-sm-12">
				<div class="form-group">
					<label>Email</label>
					<input type='email' maxlength='120' name='Email' value='' class='form-control'/>
				</div>
			</div>
			<div class="col-md-6 col-sm-12">
				<div class="form-group">
					<label>Phone</label>
					<input type='tel' maxlength='120' name='Phone' value='' class='form-control'/>
				</div>
			</div>
			<div class="col-md-6 col-sm-12">				
				<div class="form-group">
					<label>Subject</label>
					<input type='text' maxlength='255' name='Subject' value='' class='form-control'/>
				</div>
			</div>
			<div class="col-md-6 col-sm-12">	
				<div class="form-group">
					<label>Classifications</label>
					<select name='Classification' value='' class='custom-select form-control' onchange="setDependent(this, false)" id='Classification'>
						 <option value='' >-None-</option>
						 <option value='Question' >Question</option>
						 <option value='Problem' >Problem</option>
						 <option value='Feature' >Feature</option>
						 <option value='Others' >Others</option>
					</select>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label>Description</label>
					<textarea name='Description' maxlength='3000' width='250' height='250' class='form-control'></textarea>
				</div>			
			</div>
			<div class="col-md-12">
			    <input type='submit' id="zsSubmitButton_14278000000048556" class='btn btn-primary' value='Submit'>
				<input type='button' class='ml-sm-3 btn btn-secondary' value='Reset' onclick="zsResetWebForm('14278000000048556')">				
			</div>	
			</div>	
      </form>
   </div>