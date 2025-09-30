<?php

/*
 * This file is part of the TYPO3 extension f7media/f7hyphenator.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE text file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 *                     https://typo3.org/
 *
 * PHP Syllable splitting and hyphenation via vanderlee/phpSyllable
 *                         https://github.com/vanderlee/phpSyllable
 * Copyright © 2011-2025 Martijn van der Lee. MIT Open Source license applies.
 */

namespace F7\F7hyphenator\Service;

use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use Vanderlee\Syllable;

class HyphenationService
{
    /**
     * @var string[]
     */
    const BLACKLIST = [
        'TYPO3',
    ];

    public static function hyphenateString(string $text, int $minWordLength = 0, int $minTextLength = 0): string
    {
        if ($minTextLength > 0 && strlen($text) < $minTextLength) {
            return $text;
        }

        $request = $GLOBALS['TYPO3_REQUEST'];
        $request->getAttribute('site');

        // set hyphenation rules depending on current site language
        /** @var SiteLanguage $siteLanguage */
        $siteLanguage = $request->getAttribute('language');
        $syllableLang = $siteLanguage->getLocale()->getLanguageCode()  == 'en'
            ? 'en-gb'
            : $siteLanguage->getLocale()->getLanguageCode();

        $words = explode(' ', $text);

        $syllable = new Syllable\Syllable($syllableLang);
        $syllable->setCache(null);

        $processedWords = [];
        foreach ($words as $word) {
            // preserve [tags]
            $tags = preg_split('#(\[/?\w+\])#', $word, 0, PREG_SPLIT_DELIM_CAPTURE);
            if ($tags && count($tags) === 1) {
                if (in_array($word, self::BLACKLIST) || ($minWordLength > 0 && strlen($word) < $minWordLength)) {
                    $processedWords[] = $word;
                } else {
                    $processedWords[] = $syllable->hyphenateText($word);
                }
            }
            // word contains [tags] and have to be processed step by step by itself while preserving the tags
            else {
                $taggedWord = '';
                if ($tags) {
                    foreach ($tags as $tag) {
                        if (str_starts_with($tag, '[')) {
                            $taggedWord .= $tag;
                        } elseif (in_array($tag, self::BLACKLIST) || ($minWordLength > 0 && strlen($tag) < $minWordLength)) {
                            $taggedWord .= $tag;
                        } else {
                            $taggedWord .= $syllable->hyphenateText($tag);
                        }
                    }
                }

                $processedWords[] = $taggedWord;
            }
        }

        return implode(' ', $processedWords);
    }
}
