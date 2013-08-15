# Router
## Routing class to match request URL's against given routes 
and map them to a controller action.

_Copyright (c) 2013, goliatone_.
_Please reference the MIT.md file at the root of this distribution_

### Details

TODO: Standardize trailing slashes!!! Clean user routes to coform to it.

#### Namespace

`goliatone\flatg`

#### Imports

<table>

	<tr>
		<th>Alias</th>
		<th>Namespace / Class</th>
	</tr>
	
	<tr>
		<td>Route</td>
		<td>goliatone\flatg\Route</td>
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

### Instance Properties
#### <span style="color:#6a6e3d;">$_routes</span>

Array

#### <span style="color:#6a6e3d;">$_preprocessors</span>

Array

#### <span style="color:#6a6e3d;">$namedRoutes</span>

Array

#### <span style="color:#6a6e3d;">$basePath</span>

Array

#### <span style="color:#6a6e3d;">$requestUrl</span>




## Methods

### Instance Methods
<hr />

#### <span style="color:#3e6a6e;">__construct()</span>


<hr />

#### <span style="color:#3e6a6e;">reset()</span>


<hr />

#### <span style="color:#3e6a6e;">setBasePath()</span>

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
				$base_url
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				
			</td>
		</tr>
			
	</tbody>
</table>


<hr />

#### <span style="color:#3e6a6e;">map()</span>

Route factory method

##### Details

Maps the given URL to the given target.

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
				string
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
				The target of this route. Can be anything. 
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
				Array of optional arguments.
			</td>
		</tr>
			
	</tbody>
</table>


<hr />

#### <span style="color:#3e6a6e;">addPreprocess()</span>


<hr />

#### <span style="color:#3e6a6e;">matchCurrentRequest()</span>

Matches the current request against mapped routes


<hr />

#### <span style="color:#3e6a6e;">match()</span>

Match given request url and request method and see if a route has been defined for it
If so, return route's target
If called multiple times


<hr />

#### <span style="color:#3e6a6e;">checkRoute()</span>

Check if a route's URL can handle current request.


<hr />

#### <span style="color:#3e6a6e;">setRouteParams()</span>

Extract params from current request and set route's


<hr />

#### <span style="color:#3e6a6e;">generate()</span>

Reverse route a named route

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
				$route_name
			</td>
			<td>
									<a href="http://www.php.net/language.types.string.php">string</a>
				
			</td>
			<td>
				The name of the route to reverse route.
			</td>
		</tr>
					
		<tr>
			<td>
				$params
			</td>
			<td>
									<a href="http://www.php.net/language.types.array.php">array</a>
				
			</td>
			<td>
				Optional array of parameters to use in URL
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
			The url to the route
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">hasRoute()</span>


<hr />

#### <span style="color:#3e6a6e;">getRoute()</span>


<hr />

#### <span style="color:#3e6a6e;">redirect()</span>

HTTP Status Code:
301 Moved Permanently
302 Moved Temporarily
303 See Other






