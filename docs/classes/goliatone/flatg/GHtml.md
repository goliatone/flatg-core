# GHtml



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
	
</table>


## Methods
### Static Methods
<hr />

#### <span style="color:#3e6a6e;">attr()</span>

Compiles an array of HTML attributes into an attribute string and
HTML escape it to prevent malformed (but not malicious) data.

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
				$attrs
			</td>
			<td>
									<a href="http://www.php.net/language.types.array.php">array</a>
				
			</td>
			<td>
				the tag's attribute list
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
			The formatted html string.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">__callStatic()</span>

The magic call static method is triggered when invoking inaccessible
methods in a static context. This allows us to create tags from method
calls.

##### Details

Html::div('This is div content.', array('id' => 'myDiv'));

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
				$tag
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				The method name being called.
			</td>
		</tr>
					
		<tr>
			<td>
				$args
			</td>
			<td>
									<a href="http://www.php.net/language.types.array.php">array</a>
				
			</td>
			<td>
				Parameters passed to the called method.
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
			Formatted tag.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">truncate()</span>

http://stackoverflow.com/questions/8504270/how-to-truncate-string-with-html-tags-in-desired-way







