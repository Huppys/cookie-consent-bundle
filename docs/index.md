# Cookie Consent bundle for Symfony

As soon as you are collecting or processing personally identifiable information (short `PII`) you are obliged to allow
your visitors to decide what information are collected. This cookie consent banner bundle helps you to help your users.

Symfony bundle to integrate a cookie consent dialog to your website and to handle cookies according to AVG/GDPR.

## Installation

### Step 1: Download using composer

In a Symfony application run this command to install and integrate Cookie Consent bundle in your application:

```bash
composer require huppys/cookie-consent-bundle
```

### Step 2: Enable the bundle

When not using Symfony Flex, enable the bundle manually:

In AppKernel.php add the following line to the registerBundles() method:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new huppys\CookieConsentBundle\CookieConsentBundle(),
        // ...
    );
}
```

or in config/bundles.php add the following line to the array:

```php
<?php

return [
    // ...
    huppys\CookieConsentBundle\CookieConsentBundle::class => ['all' => true],
    // ...
];
```

### Step 3: Enable the routing

When not using Symfony Flex, enable the bundles routing manually by adding the following lines to your
config/routing.yml:

```yaml
cookie_consent:
  resource: "@CookieConsentBundle/config/routes.php"
```

### Step 4: Configure to your needs

By default, the most secure options are enabled. You can change the config in `config/packages/cookie_consent.yaml`:

```yaml
cookie_consent:
  consent_configuration:
    consent_cookie:
      expires: 'P180D' # available values: PHP formatted date string, 'P180D' (180 days), 'P1Y' (1 year) etc.
      domain: null # optional: string or null, domain name, e.g. 'example.com'; null means 'use the current domain'
      secure: true # boolean, true by deafult, enable or disable transport only over https
      http_only: true # boolean, refer to mdn docs for more info
      same_site: 'lax' # available values: 'strict', 'lax', 'none'; if value is 'none' the 'secure' flag will be set to true by default
    consent_categories:
      functional:
        - bookmark
        - shopping_cart
      social_media:
        - twitter
      marketing:
  persist_consent: true # boolean; logs user actions to database
  position: 'dialog' # available values: 'bottom', 'dialog'
  form_action: $routeName # When set, xhr-Requests will only be sent to this route. Take care of having the route available.
  csrf_protection: true # boolean; enable or disable csrf protection for the form
```

## Usage

### Twig implementation

Load the cookie consent in Twig via render_esi ( to prevent caching ) at any place you like:

```twig
{{ render_esi(path('cookie_consent.view')) }}
{{ render_esi(path('cookie_consent.show_if_cookie_consent_not_set')) }}
```

If you want to load the cookie consent with a specific locale you can pass the locale as a parameter:

```twig
{{ render_esi(path('cookie_consent.view', { 'locale' : 'en' })) }}
{{ render_esi(path('cookie_consent.show_if_cookie_consent_not_set', { 'locale' : app.request.locale })) }}
```

You have to install assets like javascript for asynchronous form submission and default styles. To install these assets
run:

```bash
bin/console assets:install
```

### Cookies

When a user submits the form the preferences are saved as cookies. The cookies have a lifetime of 180 days. The
following cookies are saved:

- **consent**: date of submit
- **consent-key**: Generated key as identifier to the submitted Cookie Consent of the user
- **consent-category-[CATEGORY]**: selected value of user (*true* or *false*)

In case the user rejects to usage of cookies, only the cookie named **consent** is saved with the current date as value.

### Logging

AVG/GDPR requires all given cookie preferences of users to be explainable by the webmasters. For this we log all cookie
preferences to the database. IP addresses are anonymized. You can disable logging the given consent by
setting `persist_consent` to *false*.

![Database logging](https://raw.githubusercontent.com/huppys/cookie-consent-bundle/master/docs/log.png)

### TwigExtension

The following TwigExtension functions are available:

**cookieconsent_isCategoryAllowedByUser**
Check if user has given its permission for certain cookie categories.

```twig
{% if cookieconsent_isCategoryAllowedByUser('analytics') == true %}
    ...
{% endif %}
```

**cookieconsent_isCookieConsentOptionSetByUser**
Check if user has saved any cookie preferences. This will default to *true* even when the user chose to reject all
cookies.

```twig
{% if cookieconsent_isCookieConsentOptionSetByUser() == true %}
    ...
{% endif %}
```

## Customization

### Categories

You can add or remove any category by changing the configuration option `consent_categories` and making sure there are
translations available for these categories.

### Translations

All texts can be altered via Symfony translations by overwriting the CookieConsentBundle translation files. Take a look
at ``translations`` into any of the `yaml` files to get an idea of the structure.

### Customization: Contents

Most of the blocks in this consent layer a customizable. The blocks are:

- header
    - title
    - intro
    - read_more
- pre_form
- consent_form ???
    - consent_form_start
    - required_cookies_category
    - consent_form_rest
- post_form
- scripts

Create a file : ``templates/bundles/CookieConsentBundle/cookie_consent.html.twig`` and insert the following code:

```twig
# app/templates/bundles/CookieConsentBundle/cookie_consent.html.twig
{% extends '@!CookieConsent/cookie_consent.html.twig' %} {# this extends the base layout#}

{% block title %}
    Your custom title
{% endblock %}

{% block required_cookies_category %}
    {# let's hide this block #}
{% endblock required_cookies_category %}
```

### Customization: Theming

A cookie consent form should be themeable and so is this.

#### Use symfony/form bundle

To use a different form theme from symfony/form bundle, stick to
the [Symfony documentation about rendering forms](https://symfony.com/doc/current/form/form_themes.html). Remember to
load the scripts and styles that belong to the theme.

#### Define your own form theme

This bundle comes with a default theme. To use this particular theme, please add the following line to
your ``config/packages/twig.yaml``:

```yaml
twig:
  form_themes:
    - '@CookieConsent/form/cookie_consent_form_theme.html.twig'
```

To use your very own theme, create a twig file under your templates folder and reference this twig file within
your ``config/packages/twig.yaml`` under `twig`->`form_themes` like this:

```yaml
twig:
  default_path: '%kernel.project_dir%/templates' # set template base path like this if it fits your project
  form_themes:
    - 'cookie_consent/consent_form_theme.html.twig'
```

This should include overwrites for block like `choice_row` and `submit_row`:

```twig
{% block choice_row %}
    <div class="lala">
        {% for child in form %}
            {% if not child.rendered %}
                {{- form_widget(child) -}}
                {{- form_label(child, null, {translation_domain: choice_translation_domain}) -}}
            {% endif %}
        {% endfor %}
    </div>
    <div>
        {{- form_help(form) -}}
    </div>
{% endblock %}

{% block submit_row %}
    {{ block('button_widget') }}
{% endblock %}
```

As Twig allows the loading of multiple themes, ensure that yours is the last one to overwrite blocks with the same name.

### Styling

CookieConsentBundle comes with a default styling. A sass file is available in `assets/css/cookie_consent.scss`
and a build css file is available in `public/css/cookie_consent.css`.
Colors can easily be adjusted by setting the variables available in the sass file.

To apply the default styling to your website, include the following line in your twig template:

```twig
{% include "@CookieConsent/cookie_consent_styling.html.twig" %}
```

### JavaScript: Events

When a form button is clicked, the event of `cookie-consent-form-submit-successful` is dispatched. Use the following
code to listen to the event.

```javascript
document.addEventListener('cookie-consent-form-submit-successful', function (e) {
    // ... your functionality
    // ... e.detail is available to see which button is clicked.
}, false);
```

# Troubleshoting

## Error 500 after submitting the form

If your backend returns a response with HTTP status code 500 after submitting the form, make sure you have the route
available for the configuration
option ``form_action``. 