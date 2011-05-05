<script type='text/javascript'>

function submitSearchForm()
{
        dojo.connect(
                dojo.byId('form01'),
                'onsubmit',
                function(event)
                {
			dojo.byId('form01').q.value = encodeURIComponent(dojo.byId('form01').qq.value)
                        dojo.stopEvent(event)
			dojo.require('dojox.xml.parser')
                        var xhrArgs = {
                                form: dojo.byId('form01'),
				handleAs:'text',
                                load:function(data)
                                {
					var html = '<table><tr><th>picture</th><th>name</th><th>professional headline</th></tr>'
					var linkedInId = ''
					var firstname = ''
					var lastname = ''
					var headline = ''
					var profileUrl = 'profileUrl'
					var pictureUrl = ''
					var xmldata = dojox.xml.parser.parse(data)
					var root = xmldata.documentElement
					for(var c = 0; c < root.getElementsByTagName('person').length; c++)	
					{
						for(var cc = 0; cc < root.getElementsByTagName('person')[c].childNodes.length; cc++)
						{
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'picture-url' && document.location.protocol == 'http:')
							{
								pictureUrl = "<img class='profilePicture' src='" + dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]) + "'/>"
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'public-profile-url')
							{
								profileUrl = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc])
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'headline')
							{
								headline = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'id')
							{
								linkedInId = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);	
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'first-name')
							{
                                                                firstname = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);  
							}
							if(root.getElementsByTagName('person')[c].childNodes[cc].tagName == 'last-name')
							{
                                                                lastname = dojox.xml.parser.textContent(root.getElementsByTagName('person')[c].childNodes[cc]);  
							}
					}
                                                html += '<tr><td class="picture">' + pictureUrl + '</td><td>' + firstname + ' ' + lastname + '</td><td><a class="black" target="_blank" href="' + profileUrl  +'">' + headline + '</a></td></tr>'
					}
					dojo.byId('connections').innerHTML = html + '</table>'
                                },
                                error: function(error)
                                {
                                        error_alert(error)
                                }
                        }
                        if(dojo.byId('q').value.match(/^\w/))
                        {
                                dojo.xhrPost(xhrArgs)
                        }
                }
        )
}

dojo.addOnLoad(submitSearchForm)

function listConnections()
{
	var xhrArgs = {
		url: '/api/list_connections/',
		handleAs: 'json',
		load: function(data)
		{
			var html = '<table><tr><th>from</th><th>picture</th><th>name</th><th>email</th><th>place</th><th>country</th><th>linkedIn</th><th>twitter</th><th>facebook</th></tr>'
			for(var c = 0; c < data.length; c++)
			{
					html += "<tr><td>" + 	data[c].userName + "</td><td class='picture'>" +
								(data[c].pictureUrl ? '<img class="profilePicture" src="' + data[c].pictureUrl + '"/>' : '') + '</td><td>' +
								data[c].name + "</td><td>" + 
								(data[c].email ? data[c].email : '')  + "</td><td>" + 
								(data[c].place ? data[c].place : '') + "</td><td>" + 
								(data[c].country ? data[c].country : '') + '</td><td>' +
								(data[c].headline ? data[c].headline : '') + "</td><td>"  + "</td><td>" + "</td><td>" +		
						"</td></tr>"
			}
			dojo.byId('connections').innerHTML = html + '</table>'
		},
		error: function(error)
		{
			error_alert('OK ' + error)
		}
	}
	dojo.xhrGet(xhrArgs)
}
dojo.addOnLoad(listConnections)

function listEmail()
{
	var xhrArgs = {
		url: '/api/list_emails/',
		handleAs: 'json',
		load: function(data)
		{
			var html = '<table><tr><th><?php echo PText::getString("Subject");?></th></tr>'
			for(var c = 0; c < data.length; c++)
			{
				var class = ''
				data[c].seen ? class='seen' : class=''; 
				html += '<tr><td class="' + class + '">' + data[c].subject + '</td</tr>'
			}
			dojo.byId('connections').innerHTML = html + '</table>'
		},
		error:function(error)
		{
			error_alert(error)
		}
	}
	dojo.xhrGet(xhrArgs)
}
</script>

<div id='menuBar'>
	<div class='floatleft'>
	<a class='bolditalic' href=''>list/itemview</a> | <a class='bolditalic' href='javascript:listEmail()'>email</a> | <a class='bolditalic' href='/admin/'>events</a> | <a class='bolditalic' href='/config/'>settings</a>
	</div>
	<div class='floatright'>
                <form class='inlineForm' action='/search/' id='form01' method='POST'>
                        <input type='radio' name='select' value='linkedin' checked/> LinkedIn
			<input type='radio' name='select' value='linkediniscompany' /> company
			<input type='radio' name='select' value='local'/> local
			<input class='inlineInput' name='country' size='2'/> country
			<input type='hidden' name='q' id='q'/>
                        <input class='inlineInput' name='qq' type='text'/> 
                        <input class='inlineInput' type='image' src='/admin/img/search.png'/>
                </form>
	</div>
</div>

<div id='connections'></div>
