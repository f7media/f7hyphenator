[![TYPO3 compatibility](https://img.shields.io/badge/TYPO3-12.4-ff8700?maxAge=3600&logo=typo3)](https://get.typo3.org/)
[![TYPO3 compatibility](https://img.shields.io/badge/TYPO3-13.4-ff8700?maxAge=3600&logo=typo3)](https://get.typo3.org/)

# TYPO3 Extension `f7hyphenator`

The "f7hyphenator" extension provides a ViewHelper to add &shy; word breakpoints
based on [vanderlee/phpSyllable](https://github.com/vanderlee/phpSyllable) in
Fluid.

This helps ensuring that longer words break up more nicely inside their
containers when displayed in responsive layout.

## Features

- plain and simple: a Fluid ViewHelper to use wherever you need it
- Language is automatically set based on current context of rendered FE page
- Alternatively you could also use the stateless service  inside php

## Usage

```xml
<f7:hyphenator>{record.title}</f7:hyphenator>
<f7:hyphenator minTextLength="8" minWordLength="12">{record.title}</f7:hyphenator>
```

Alternative **inline notation** and enchaining
```html
{record.title -> f7:hyphenator()}
{record.title -> f7:hyphenator(minWordLength: 12)}
{record.title -> f7:hyphenator() -> f:format.stripTags()}
```

If needed, you could also directly **call the stateless service** directly from **PHP**:
```php
use F7\F7hyphenator\Service\HyphenationService;

$hyphenatedText = HyphenationService::hyphenateString($text, $minWordLength, $minTextLength);
```

### Settings

| Parameter       | Required | Type | Default | Description                                             |
|-----------------|:--------:|:----:|:-------:|---------------------------------------------------------|
| `minWordLength` |    no    | int  |    0    | words shorter than this value won't be processed        |
| `minTextLength` |    no    | int  |    0    | texts shorter than this value won't be processed at all |

## Known Issues

* Blacklist is currently hardcoded in `HyphenationService`
* Blacklisted words are not recognised in dash-separated words (e.g. blacklisted "TYPO3" in "TYPO3-Website")

## Contact

For any inquiries or support requests, please contact [F7 Media GmbH](https://f7.de).

|                  | URL                                                  |
|------------------|------------------------------------------------------|
| **Repository:**  | https://github.com/f7media/f7hyphenator/             |
| **TER:**         | https://extensions.typo3.org/extension/f7hyphenator/ |
