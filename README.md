# Twig mimic

For Twig template creator.


# NOTICE 

This tool is TOO DANGER. DON'T use public service. use in ONLY development.


# install and start

```
$ make setup
$ make start
# open http://127.0.0.1:8080
```


# how to write template(and data) 

- write twig template in `/template`. (ex: `Hoge.twig`)
- write ViewModel(Data) in `/src/ViewState/`. (ex: `Hoge.php`)
- `make start` and open `http://localhost:8080/Hoge`

> `Sample` is sample.


# auto loading

You don't need write route settings.

URL `/Someting` will be map to `/template/Something.twig`, `/src/ViewState/Something.php`(`\Project\ViewState\Something` class)

also `/Some/Thing` => `/template/Some/Thing.twig`, `ViewState\Some\Thing`

> `/` is special, you can't use.

# LICENSE

MIT
