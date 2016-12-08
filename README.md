# Daux.io Parsedown Generator

This processor uses Parsedown Extra when generating a site with Daux.io

Additionally it converts specifically formatted blockquotes to callouts.

## Usage

```
vendor/bin/daux --processor=ParsedownProcessor
```

## Callouts

If you format the a blockquote in the following manner you can create a callout:

```markdown
> #### Type::Title 
> Callout Body 
> ####
```

The rendered html will be formatted like this:

```html
<div class="callout callout-type">
    <div class="callout-header">Title</div>
    <div class="callout-body">
        <p>Callout Body</p>
    </div>
</div>
```

Any value can be entered for `Type`. The `Type` will always be converted to the class `callout-type`.

Example:

```markdown
> #### Alert::This is how you enter things! 
> This can contian any valid _markdown_.
>
> Just like a normal blockquote.
> ####
```

The rendered html will be formatted like this:

```html
<div class="callout callout-alert">
    <div class="callout-header">Alert: This is how you enter things!</div>
    <div class="callout-body">
        <p>This can contain any valid <em>markdown</em>.</p>
        <p>Just like a normal blockquote.</p>
    </div>
</div>
```

If no Title is provided, the Header will just contain the `Type`.

Example:

```markdown
> #### Warning:: 
> A warning you should be aware of.
> ####
```

The rendered html will be formatted like this:

```html
<div class="callout callout-warning">
    <div class="callout-header">Warning</div>
    <div class="callout-body">
        <p>A warning you should be aware of.</p>
    </div>
</div>
```

### Callout Styles

In the `less` directory you will find a basic styles. The less is modified from Bootstrap's alerts and contains styles for:

- success (green)
- note (blue)
- alert (red)
- warning (yellow)
- default (grey) - matches all other callouts that aren't success, note, alert, or warning.
