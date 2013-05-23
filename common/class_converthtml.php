<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class CConvertHtml
{
	/**
	 * @var string
	 */
	protected $sHtml;
	
	/**
	 * @var string
	 */
	protected $sText;
	
	/**
	 * @var int
	 */
	protected $iWidth;
	
	/**
	 * @var array
	 */
	protected $aSearch;
	
	/**
	 * @var array
	 */
	protected $aReplace;
	
	/**
	 * @var bool
	 */
	protected $bIsRep;

	/**
	 * @param string $sHtml = ''
	 * @return void
	 */
	public function CConvertHtml($sHtml = '')
	{
		$this->iWidth = 75;
		
		$this->aSearch = array(
			"/\r/",
			"/[\n\t]+/",                        
			'/<script[^>]*>.*?<\/script>/i',    
			'/<style[^>]*>.*?<\/style>/i',
			'/<title[^>]*>.*?<\/title>/i',
			'/<h[123][^>]*>(.+?)<\/h[123]>/i', 
			'/<h[456][^>]*>(.+?)<\/h[456]>/i', 
			'/<p[^>]*>/i',          
			'/<br[^>]*>/i',         
			'/<b[^>]*>(.+?)<\/b>/i',
			'/<i[^>]*>(.+?)<\/i>/i',
			'/(<ul[^>]*>|<\/ul>)/i',
			'/(<ol[^>]*>|<\/ol>)/i',
			'/<li[^>]*>/i', 
			'/<a[^>]*href="([^"]+)"[^>]*>(.+?)<\/a>/i', 
			'/<hr[^>]*>/i',               
			'/(<table[^>]*>|<\/table>)/i',
			'/(<tr[^>]*>|<\/tr>)/i',      
			'/<td[^>]*>(.+?)<\/td>/i',    
			'/<th[^>]*>(.+?)<\/th>/i',    
			'/&nbsp;/i',
			'/&quot;/i',
			'/&gt;/i',
			'/&lt;/i',
			'/&amp;/i',
			'/&copy;/i',
			'/&trade;/i',
			'/&#8220;/',
			'/&#8221;/',
			'/&#8211;/',
			'/&#8217;/',
			'/&#38;/',
			'/&#169;/',
			'/&#8482;/',
			'/&#151;/',
			'/&#147;/',
			'/&#148;/',
			'/&#149;/',
			'/&reg;/i',
			'/&bull;/i',
			'/&[&;]+;/i',
			'/&#39;/',
			'/&#160;/'
		);
		
		$this->aReplace = array(
			'',								
			' ',							
			'',								
			'',
			'',
			"\n\n\\1\n\n",
			"\n\n\\1\n\n", 
			"\n\n\t",
			"\n",   
			'\\1',	
			'\\1',	
			"\n\n",  
			"\n\n",  
			"\n\t* ",
			'\\2 (\\1)',
			"\n------------------------------------\n",
			"\n",                     
			"\n",                     
			"\t\\1\n",                
			"\t\\1\n",
			' ',
			'"',
			'>',
			'<',
			'&',
			'(c)',
			'(tm)',
			'"',
			'"',
			'-',
			"'",
			'&',
			'(c)',
			'(tm)',
			'--',
			'"',
			'"',
			'*',
			'(R)',
			'*',
			'',
			'\'',
			''
		);
		
		$this->bIsRep = false;
		
		if (!empty($sHtml))
		{
			$this->SetHtml($sHtml);
		}
	}

	/**
	 * @param string $sSource 
	 * @return void
	 */
	public function SetHtml($sSource)
	{
		$this->sHtml = str_replace('$', 'S', $sSource);
		$this->bIsRep = false;
	}

	/**
	 * @param int $iWidth 
	 * @return void
	 */
	public function SetTextMaxWidth($iWidth)
	{
		$this->iWidth = $iWidth;
	}

	/**
	 * @return string
	 */
	public function GetText()
	{
		if (!$this->bIsRep) 
		{
			$this->convert();
		}
		
		return $this->sText;
	}

	/**
	 * @return void 
	 */
	protected function convert()
	{
		$sText = trim(stripslashes($this->sHtml));
		$sText = preg_replace('/[\s]+/', ' ', $sText);
		$sText = preg_replace($this->aSearch, $this->aReplace, $sText);
		$sText = str_ireplace('<div>',"\n<div>", $sText);
		$sText = strip_tags($sText, '');
		$sText = preg_replace("/\n\\s+\n/", "\n", $sText);
		$sText = preg_replace("/[\n]{3,}/", "\n\n", $sText);

		if ($this->iWidth > 0) 
		{
			$sText = wordwrap($sText, $this->iWidth);
		}

		$this->sText = $sText;
		$this->bIsRep = true;
	}
}
