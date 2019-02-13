# Chroma Components
A collection of components which extend, override or otherwise introduce functionality to our PHP and JS architecture.

The nature of these components does not have a universally applicable implementation, encapsulation or size. Instead, they range in size and scope, from tiny extentions to integrated applications.
This project is essentially a plugin of plugins, with a simple inclusion mechanism which searches for a controller PHP file, where the remaining functionality is designated. 

```php
/* Components Manifest (Requires) */
foreach(glob(plugin_dir_path( __FILE__ ) . "components/*/*.php") as $component) {
	require $component;
}
```
___

There are some great scalability benefits of structuring the project this way. From a developer's experience standpoint, it's a single repository to manager, in which the developer can create multiple branches and deploy updates to multiple sites with ease. It's less maintenance time, so that you can spend more time developing and not fussing with multiple codebases. It also results in a faster turn around of features and more robust dynamic code. The downside is that commit history can be confusing, and having to roll back changes unrelated to the issue can be frustrating. Also it could contribute to unused and unnecessary code and features, but as long as your restricting why and the amount of front end assets you're loading, there shouldn't be any major performance drawbacks.
___

### Adding New Components
1. Inside the components subdirectory, create a new directory with the "Feature" name.
2. Inside the feature directory, create a new PHP file that serves as an "index" for the feature. 
3. The index may be a controller for any custom REST endpoints, action hooks, template routing, asset enqueueing and file includes for other modules that are part of the feature.
___

### Front End Assets
For javascript projects, a webpack config is available in which you can designate entry points and the output files will result within the /dist directory. Note that because this is WordPress based, you will have to manually enqueue your scripts and styles where applicable or include them via other build setups, such as the theme gulp-based build tooling. 

