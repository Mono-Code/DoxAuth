# WordPress - Doximity Login

Allow your members to register/login to your WordPress website by leveraging Doximity's OAuth (2.0) service.

## Getting Started

This plugin requires implementation in the theme layer.

### 1. Setup initial credential/outcome handeling settings:

Apply Doximity OAuth credentials & outcome routing "Settings" > "Doximity&reg; Authentication" page.

### 2. Create a template for routing the requests in theme layer:

```
<?php ( new DoxAuth_Wrapper )->route_request(); ?>
```


### 3. Create/Assign a routing page to use the above mentioned template

### 4. Create/Implement link for logging in to wp:

```
<a href="[routing page from step 3]?doximity_type=login">Login Using Doximity</a>
```

### 5. Create/Implement link for a logged-in member to pair their wp account with their Doximity account:

```
<a href="[routing page from step 3]?doximity_type=verify">Link with Doximity Account</a>
```

### 6. Create/Implement link for a logged-in member to unpair their wp account from their Doximity account:

```
<a href="[routing page from step 3]?doximity_type=unlink">Remove Link With Doximity Account</a>
```

## Built With

* [Composer](https://getcomposer.org/) - PHP Package Manager
* [OAuth2-Client](https://github.com/thephpleague/oauth2-client) - Handles Core OAuth (2.0)
* [GuzzleHTTP](http://guzzlephp.org) - Handels HTTP Requests
* [Random_Compat](https://github.com/paragonie/random_compat) - PHP 5.x support for random_bytes() and random_init()
