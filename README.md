#WP Code Prettify Syntax Highlighter

Works with all google code prettify languages.

Usage:

```
<div>
  <pre class="prettifier lang-*">
    <div>
      <div>Hello World</div>
    </div>
  </pre>
</div>
```

Better than existing WordPress options because you can indent the code in your pre tags.

In other plugins, you must do this:

```
<div>
<pre class="prettifier lang-*">
<div>
  <div>Hello World</div>
</div>
</pre>
</div>
```

#### Deployment

Copy everything from this directory to the SVN directory

Then:

```
svn cp trunk tags/TAGNO
svn ci -m "tagging version TAGNO"
```
