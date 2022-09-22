# Templating

All views are stored in `views/` and have an `.ptml` extension. The view is an extension of `src/helpers/Template`.

## Comments

```twig

```

## Blocks and Yields

Use of block

```twig
{% block content %}
    <main class="container">
        <div class="jumbotron">
            <div class="dashboard">
                Hello World!!
            </div>
        </div>
    </main>
{% endblock %}
```

Use of yield

A sample layout file looks like:

```twig
<html>
    <head>
        ...
        <title>{% yield title %}</title>
        ...
        {% yield stylesheets %}
    </head>
    <body>
        {% yield content %}

        {% yield scripts %}
    </body>
</html>
```

