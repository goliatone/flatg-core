# Config



### Extends

`ArrayObject`

#### Namespace

`goliatone\flatg\config`

#### Imports

<table>

	<tr>
		<th>Alias</th>
		<th>Namespace / Class</th>
	</tr>
	
	<tr>
		<td>ArrayObject</td>
		<td>ArrayObject</td>
	</tr>
	
</table>

## Properties

### Instance Properties
#### <span style="color:#6a6e3d;">$_cache</span>

#### <span style="color:#6a6e3d;">$_config</span>

#### <span style="color:#6a6e3d;">$_environment</span>




## Methods

### Instance Methods
<hr />

#### <span style="color:#3e6a6e;">__construct()</span>

Config constructor.

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
				$initialize
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				Can be either an array
			</td>
		</tr>
			
	</tbody>
</table>


<hr />

#### <span style="color:#3e6a6e;">get()</span>

Retrieve a configuration setting identified by they 
`$key` parameter.

##### Details

They key can be a path in dot syntax, where the 
last segment is the property retrieved.
```php
$key=backend_storage.dropbox.api_key;
$config['backend_storage']['dropbox']['api_key'];
```
If the `$key` parameter is `NULL` the method returns
the entire config.

The `$default` parameter lets you specify the returned
value in case the requested setting is undefined.
If `$default` is not provided it will return `NULL`

```php
$apiKey = $config->get('backend_storage.dropbox.api_key');

$theme = $config->get('theme', 'default');
```

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
				$key
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Setting's unique identifier.
			</td>
		</tr>
					
		<tr>
			<td>
				$default
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				The default value.
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			mixed
		</dt>
		<dd>
			It will setting value or `$default`
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">set()</span>

Register one or more configuration settings values.

##### Details

Configuration settings are typically defined by 
returning an array from a file stored under a 
registered config folder. However, it is
also possible to explicitly set configuration values using
the Config class.

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
				$key
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Setting's unique identifier.
			</td>
		</tr>
					
		<tr>
			<td>
				$value
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				The default value.
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			goliatone\flatg\config\Config
		</dt>
		<dd>
			Chainable method
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">del()</span>

We mainly have this here to use on the 
ArrayObject interface.
REAME: This does not work for dot paths!


<hr />

#### <span style="color:#3e6a6e;">init()</span>


<hr />

#### <span style="color:#3e6a6e;">load()</span>


<hr />

#### <span style="color:#3e6a6e;">loadEnvironment()</span>


<hr />

#### <span style="color:#3e6a6e;">import()</span>

TODO: We could implement a strategy pattern and have the 
import/save methods be implemented that way. We could
handle different config formats: PHP, JSON, YAML, PHP.ini, 
XML, etc. It would make more sense if this becomes a lib
outside of FlatG.


<hr />

#### <span style="color:#3e6a6e;">save()</span>


<hr />

#### <span style="color:#3e6a6e;">format()</span>


<hr />

#### <span style="color:#3e6a6e;">_cacheGet()</span>


Notice: Array to string conversion in /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class_methods.php on line 7

Call Stack:
    0.0003     236640   1. {main}() /Users/emilianoburgos/Development/PHP/flatg/sage.php:0
    0.1714   13684008   2. Dotink\Sage\Writer->buildDocumentation() /Users/emilianoburgos/Development/PHP/flatg/sage.php:22
    0.1719   13688184   3. Dotink\Sage\Writer->write() /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/src/Writer.php:109
    0.1818   13734656   4. include('/Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class.php') /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/src/Writer.php:446
    0.1840   13752600   5. include('/Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class_methods.php') /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class.php:43

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
				$key
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Setting's unique identifier
			</td>
		</tr>
					
		<tr>
			<td>
				$getter
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				Function to retrieve value or the 
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			mixed
		</dt>
		<dd>
			Returns the result after accessing cache.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">_configMerge()</span>


Notice: Array to string conversion in /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class_methods.php on line 7

Call Stack:
    0.0003     236640   1. {main}() /Users/emilianoburgos/Development/PHP/flatg/sage.php:0
    0.1714   13684008   2. Dotink\Sage\Writer->buildDocumentation() /Users/emilianoburgos/Development/PHP/flatg/sage.php:22
    0.1719   13688184   3. Dotink\Sage\Writer->write() /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/src/Writer.php:109
    0.1818   13734656   4. include('/Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class.php') /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/src/Writer.php:446
    0.1840   13752600   5. include('/Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class_methods.php') /Users/emilianoburgos/Development/PHP/flatg/vendor/dotink/sage/templates/class.php:43

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
				$config
			</td>
			<td>
									<a href="http://www.php.net/language.types.array.php">array</a>
				
			</td>
			<td>
				Array object with want to merge 
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			goliatone\flatg\config\Config
		</dt>
		<dd>
			Chainable method
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">_mergeEnvironment()</span>

Merge the items in the given file into the items.

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
				$filename
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Path to original config file
			</td>
		</tr>
					
		<tr>
			<td>
				$items
			</td>
			<td>
									<a href="http://www.php.net/language.types.array.php">array</a>
				
			</td>
			<td>
				Array object with want to merge 
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			goliatone\flatg\config\Config
		</dt>
		<dd>
			Chainable method
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">getNestedValue()</span>

Note that the visibility of this method is set to public
to be accessible inside the cache closure.

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
				$target
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				Source array we traverse to 
			</td>
		</tr>
					
		<tr>
			<td>
				$key
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Unique identifier to resource.
			</td>
		</tr>
					
		<tr>
			<td>
				$default
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				Default value if no resource is
			</td>
		</tr>
			
	</tbody>
</table>


<hr />

#### <span style="color:#3e6a6e;">getSource()</span>

For now, this are here mainly 
for unit testing. I might switch
to use Reflection and remove them.


<hr />

#### <span style="color:#3e6a6e;">getCache()</span>

Access to the cache array.


<hr />

#### <span style="color:#3e6a6e;">offsetGet()</span>

ArrayObject interface.


<hr />

#### <span style="color:#3e6a6e;">offsetSet()</span>

ArrayObject interface.


<hr />

#### <span style="color:#3e6a6e;">offsetUnset()</span>

ArrayObject interface.




### Inherited Methods

[`ArrayObject::offsetExists()`](#offsetExists) [`ArrayObject::append()`](#append) [`ArrayObject::getArrayCopy()`](#getArrayCopy) [`ArrayObject::count()`](#count) [`ArrayObject::getFlags()`](#getFlags) [`ArrayObject::setFlags()`](#setFlags) [`ArrayObject::asort()`](#asort) [`ArrayObject::ksort()`](#ksort) [`ArrayObject::uasort()`](#uasort) [`ArrayObject::uksort()`](#uksort) [`ArrayObject::natsort()`](#natsort) [`ArrayObject::natcasesort()`](#natcasesort) [`ArrayObject::unserialize()`](#unserialize) [`ArrayObject::serialize()`](#serialize) [`ArrayObject::getIterator()`](#getIterator) [`ArrayObject::exchangeArray()`](#exchangeArray) [`ArrayObject::setIteratorClass()`](#setIteratorClass) [`ArrayObject::getIteratorClass()`](#getIteratorClass) 



