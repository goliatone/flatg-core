# GHelper



#### Namespace

`goliatone\flatg`

#### Imports

<table>

	<tr>
		<th>Alias</th>
		<th>Namespace / Class</th>
	</tr>
	
	<tr>
		<td>stdClass</td>
		<td>stdClass</td>
	</tr>
	
	<tr>
		<td>ErrorException</td>
		<td>ErrorException</td>
	</tr>
	
	<tr>
		<td>View</td>
		<td>goliatone\flatg\View</td>
	</tr>
	
	<tr>
		<td>Storage</td>
		<td>goliatone\flatg\backend\Storage</td>
	</tr>
	
	<tr>
		<td>DefaultController</td>
		<td>goliatone\flatg\controllers\DefaultController</td>
	</tr>
	
	<tr>
		<td>Event</td>
		<td>goliatone\events\Event</td>
	</tr>
	
</table>


## Methods
### Static Methods
<hr />

#### <span style="color:#3e6a6e;">getPathFromClassName()</span>

TODO: Remove?


<hr />

#### <span style="color:#3e6a6e;">fromCamelCase()</span>

TODO: Remove?


<hr />

#### <span style="color:#3e6a6e;">mergeAsObjects()</span>

TODO: Remove?


<hr />

#### <span style="color:#3e6a6e;">arrayToObject()</span>


<hr />

#### <span style="color:#3e6a6e;">appendFilenameToPath()</span>


<hr />

#### <span style="color:#3e6a6e;">removeTrailingSlash()</span>


<hr />

#### <span style="color:#3e6a6e;">ensureTrailingSlash()</span>


Notice: Array to string conversion in /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class_methods.php on line 7

Call Stack:
    0.0003     236640   1. {main}() /Users/emilianoburgos/Development/PHP/flatg/sage.php:0
    0.1714   13684008   2. Dotink\Sage\Writer->buildDocumentation() /Users/emilianoburgos/Development/PHP/flatg/sage.php:22
    0.1719   13688184   3. Dotink\Sage\Writer->write() /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/src/Writer.php:109
    0.1975   13706984   4. include('/Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class.php') /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/src/Writer.php:446
    0.1980   13717688   5. include('/Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class_methods.php') /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class.php:37

Array

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$path
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Source path.
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			string
		</dt>
		<dd>
			Source path with trailing slash.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">removeFilesFromDir()</span>







