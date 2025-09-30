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
 */

namespace F7\F7hyphenator\ViewHelpers;

use F7\F7hyphenator\Service\HyphenationService;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * PHP string parsing to add hyphenation to soft wrap multiline strings
 * \F7\F7hyphenator\Service\HyphenationService::hyphenateString($text, $minWordLength, $minTextLength);
 *
 * Examples
 * ========
 *
 * Default
 * -------
 *
 * ::
 *
 *    <f7:hyphenator>{record.title}</f7:hyphenator>
 *    <f7:hyphenator minTextLength="8" minWordLength="12">{record.title}</f7:hyphenator>
 *
 * Adds ``&shy;`` inside of words where the string could softwrap
 *
 * Inline notation
 * ---------------
 *
 * ::
 *
 *    {record.title -> f7:hyphenator()}
 *    {record.title -> f7:hyphenator(minWordLength: 12)}
 *    {record.title -> f7:hyphenator() -> f:format.stripTags()}
 *
 * Adds ``&shy;`` inside of words where the string could softwrap
 */
class HyphenatorViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'minWordLength',
            'int',
            "words shorter than this value won't be processed",
            false,
            0
        );
        $this->registerArgument(
            'minTextLength',
            'int',
            "texts shorter than this value won't be processed at all",
            false,
            0
        );
    }

    /**
     * @param array{minWordLength:int,minTextLength:int} $arguments
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $text = $renderChildrenClosure();
        $minWordLength = $arguments['minWordLength'];
        $minTextLength = $arguments['minTextLength'];

        return is_null($text) ? '' : HyphenationService::hyphenateString($text, $minWordLength, $minTextLength);
    }
}
