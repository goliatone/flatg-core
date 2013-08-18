# FlatG
## Main interface. WIP.

_Copyright (c) 2013, goliatone_.
_Please reference the MIT.md file at the root of this distribution_

### Details

TODO: Figure out what mysql wrapper to use.
TODO: Implemente defaults for config. Have a configure method
and instead of doing self::$config['prop']

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

#### Authors

<table>
	<thead>
		<th>Name</th>
		<th>Handle</th>
		<th>Email</th>
	</thead>
	<tbody>
	
		<tr>
			<td>
				Goliatone
			</td>
			<td>
				
			</td>
			<td>
				hello@goliatone.com
			</td>
		</tr>
	
	</tbody>
</table>

## Properties
### Static Properties
#### <span style="color:#6a6e3d;">$config</span>

Static config holder

#### <span style="color:#6a6e3d;">$_container</span>

Static container holder

#### <span style="color:#6a6e3d;">$router</span>

Router facade.

#### <span style="color:#6a6e3d;">$articles</span>

Articles facade.

#### <span style="color:#6a6e3d;">$markdown</span>

Markdown instance





## Methods
### Static Methods
<hr />

#### <span style="color:#3e6a6e;">initialize()</span>


<hr />

#### <span style="color:#3e6a6e;">container()</span>


<hr />

#### <span style="color:#3e6a6e;">scriptURL()</span>


<hr />

#### <span style="color:#3e6a6e;">map()</span>

Map url resource to handler implementation.

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
				$routeUrl
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Resource string that represents the URL to be mapped.
			</td>
		</tr>
					
		<tr>
			<td>
				$target
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				Handler for the provided route.
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
				Options.
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			goliatone\flatg\Router
		</dt>
		<dd>
			Router instance, chainable method.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">preprocess()</span>


<hr />

#### <span style="color:#3e6a6e;">run()</span>

TODO: dry, CLEAN.
TODO: Use EventDispatcher, foget callback madness!


<hr />

#### <span style="color:#3e6a6e;">render()</span>


<hr />

#### <span style="color:#3e6a6e;">renderJSON()</span>


<hr />

#### <span style="color:#3e6a6e;">renderXML()</span>


<hr />

#### <span style="color:#3e6a6e;">renderView()</span>

TODO: Nested render views?


<hr />

#### <span style="color:#3e6a6e;">assetUri()</span>

Echoes the asset uri with the prepended base path.
TODO: Make clean interface. We should be able to not
append anything and not specify asset dir.

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
				$asset
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				Asset url.
			</td>
		</tr>
					
		<tr>
			<td>
				$base_url
			</td>
			<td>
									<a href="http://www.php.net/language.pseudo-types.php">mixed</a>
				
			</td>
			<td>
				Base url to prepend.
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
			absolute url to the asset.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">isAJAX()</span>


<hr />

#### <span style="color:#3e6a6e;">getThemeDir()</span>


<hr />

#### <span style="color:#3e6a6e;">version()</span>

TODO: Rename, FlatG::credits


<hr />

#### <span style="color:#3e6a6e;">dump()</span>


<hr />

#### <span style="color:#3e6a6e;">synchronize()</span>

TODO: Move to module.


<hr />

#### <span style="color:#3e6a6e;">initialCommit()</span>


<hr />

#### <span style="color:#3e6a6e;">featuredArticle()</span>

Convinience method to access featured article.
TODO: How do we set this, manage? From metadata?!
TODO: Move to ArticleModel, which should be Notes?

###### Returns

<dl>
	
		<dt>
			string
		</dt>
		<dd>
			Slug of featured ArticleModel
		</dd>
	
</dl>




### Instance Methods
<hr />

#### <span style="color:#3e6a6e;">__constructor()</span>

Creates a new FlatG instance

###### Returns

<dl>
	
		<dt>
			void
		</dt>
		<dd>
			Provides no return value.
		</dd>
	
</dl>






