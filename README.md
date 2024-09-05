```php
$term = 'exampel'; // Exemplo de termo incorreto
$suggestion = did_you_mean_suggest($term);

if ($suggestion) {
    echo "Did you mean: " . $suggestion;
}
```

# References

- http://www.hackingwithphp.com/16/7/0/spellchecking-and-text-matching