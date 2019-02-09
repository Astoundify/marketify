## Versioning

[Semantic Versioning](http://semver.org/) is used. Any release that makes a change that is not a regression from the previously release should be a minor release. 

## Creating a Release

1. Create a `release/x.x.x` branch off of master.
2. Add features or fix bugs. See sections below.
3. Assign at least one reviewer other than yourself to the Pull Request.
4. Once reviewed the reviewer can merge the release in to the `master` branch.

## Create a Release

### Update `readme.txt`

[Add a meaningful list of changes](https://github.com/Astoundify/marketify/blob/master/readme.txt#L60) made in the new release.

### Bump Version Number

3 files need a version bump:

- [_theme.css](https://github.com/Astoundify/marketify/blob/master/css/_theme.css#L7)
- [readme.txt](https://github.com/Astoundify/marketify/blob/master/readme.txt#L5)
- [package.json](https://github.com/Astoundify/marketify/blob/master/package.json#L4)

### Rebuild Files

From a clean working directory:

```
$ npm install
$ grunt build
```

### Tag Release

[Create a new release on Github](https://github.com/Astoundify/marketify/releases/new). No binary needs generation, but it is a good idea to manually create a `.zip` file formatted with the version number, that extracts to `marketify-3.0.0.zip` > `marketify.zip` > `marketify`
