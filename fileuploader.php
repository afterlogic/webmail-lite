<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

abstract class qqAUploadedFile
{
	/**
	 * @var CTempFiles
	 */
	protected $oTempFiles;

	/**
	 * @var array
	 */
	protected $aResult;

	public function __construct(/* @var $oTempFiles CTempFiles */ $oTempFiles)
	{
		$this->oTempFiles = $oTempFiles;
		$this->aResult = array('success' => false, 'status' => UnknownUploadError);
	}

	function getIsInline()
	{
		return (isset($_REQUEST['inline_image']) && '1' === (string) $_REQUEST['inline_image'] &&
			0 === strpos($this->getFinalMimeType(), 'image'));
	}

	function getFinalMimeType()
	{
		$sMime = $this->getMimeType();
		if ($sMime === 'application/octet-stream' || empty($sMime))
		{
			$sMime = ConvertUtils::GetContentTypeFromFileName($this->getName());
		}
		return $sMime;
    }

	function generateSuccesArray($sTempName)
	{
		$sAddStr = ($this->getIsInline()) ? 'img&' : '';

		$sFileName = $this->getName();
		$sViewUrl = (substr(strtolower($sFileName), -4) == '.eml')
			? 'message-view.php?type='.MESSAGE_VIEW_TYPE_ATTACH.'&tn='.urlencode($sTempName)
			: 'view-image.php?img&tn='.urlencode($sTempName).'&filename='.urlencode($sFileName);
		
		$this->aResult = array(
			'fileName'	=> $this->getName(),
			'tempName'	=> $sTempName,
			'size'		=> $this->getSize(),
			'mimeType'	=> $this->getFinalMimeType(),
			'inline'	=> $this->getIsInline(),
			'url'		=> 'attach.php?'.$sAddStr.'tn='.urlencode($sTempName).'&filename='.urlencode($sFileName),
			'view'		=> $sViewUrl,
			'success'	=> true,
			'status'	=> AttachmentComplete
		);
	}

	function getFileSuccesArray()
	{
		return $this->aResult;
	}
}

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr extends qqAUploadedFile
{
	protected $_sTempName;

	public function __construct(/* @var $oTempFiles CTempFiles */ $oTempFiles)
	{
		parent::__construct($oTempFiles);
		
		$this->_sTempName = $this->generateTempName();
	}
	
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save()
	{
        $input = fopen("php://input", "r");
        $temp = tmpfile();
		if (!is_resource($input) || !is_resource($temp))
		{
			return false;
		}
		
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
		unset($input);
        
        if ($realSize != $this->getSize())
		{
            return false;
        }

		$sTempName = $this->oTempFiles->GetNextTempName($this->getTempName());

        if (false !== $this->oTempFiles->SaveFileFromStream($sTempName, $temp))
		{
			$this->generateSuccesArray($sTempName);
			return true;
		}
		return false;
    }
	
    function getName()
	{
        return isset($_GET['qqfile']) ? $_GET['qqfile'] : '';
    }

	function getTempName()
	{
        return $this->_sTempName;
    }

	function getMimeType()
	{
        return ConvertUtils::GetContentTypeFromFileName($this->getName());
    }

    function getSize()
	{
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }

	function generateTempName()
	{
		return 'XhrUpl_'.md5(uniqid().microtime(true));
	}
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm extends qqAUploadedFile
{
	const FILE_INDEX = 'qqfile';

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save()
	{
		$sPostTempName = $this->getTempName();
		$sDataTempName = $this->oTempFiles->GetNextTempName(basename($sPostTempName));

        if ($this->oTempFiles->MoveUploadedFile($sPostTempName, $sDataTempName))
		{
			$this->generateSuccesArray($sDataTempName);
			return true;
		}
		return false;
    }
	
    function getName()
	{
        return isset($_FILES[self::FILE_INDEX], $_FILES[self::FILE_INDEX]['name'])
			? $_FILES[self::FILE_INDEX]['name'] : '';
    }
	function getTempName()
	{
		return isset($_FILES[self::FILE_INDEX], $_FILES[self::FILE_INDEX]['tmp_name'])
			? $_FILES[self::FILE_INDEX]['tmp_name'] : '';
    }
    function getSize()
	{
		return isset($_FILES[self::FILE_INDEX], $_FILES[self::FILE_INDEX]['size'])
			? (int) $_FILES[self::FILE_INDEX]['size'] : 0;
    }
	function getMimeType()
	{
		return isset($_FILES[self::FILE_INDEX], $_FILES[self::FILE_INDEX]['type'])
			? trim($_FILES[self::FILE_INDEX]['type']) : '';
    }
}

class qqFileUploader {
    private $sizeLimit = 10485760;
    private $file;

    function __construct(/* @var $tempFiles CTempFiles */ $tempFiles, $sizeLimit = 10485760)
	{
        $this->sizeLimit = $sizeLimit;       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr($tempFiles);
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm($tempFiles);
        } else {
            $this->file = false; 
        }
    }

	function isInline()
	{
		if ($this->file)
		{
			return $this->file->getIsInline();
		}
		return false;
	}

	function handleUpload()
	{
		if (!$this->file){
			return array('succes' => false,'status' => NoFileUploaded);
		}

		$size = $this->file->getSize();

		if ($this->sizeLimit > 0 && $size > $this->sizeLimit) {
			return array('succes' => false,'status' => FileIsTooBig);
		}

		if ($this->file->save())
		{
			return $this->file->getFileSuccesArray();
		}
		else
		{
			return array('succes' => false,'status'=> CouldNotSaveUploadedFile);
		}
	}
}

/* uploader */
defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
include_once WM_ROOTPATH.'application/include.php';
$oSettings =& CApi::GetSettings();

$bAddImg = false;
$aResult = array('success' => false, 'status' => 'An unknown file upload error occurred.');

$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, false);
$oAccount = AppGetAccount($iAccountId);
/* @var $oAccount CAccount */
if ($oAccount)
{
	AppIncludeLanguage($oAccount->User->DefaultLanguage);

	require_once WM_ROOTPATH.'common/class_convertutils.php';
	require_once WM_ROOTPATH.'common/class_tempfiles.php';

	ConvertUtils::SetLimits();

	$oTempFiles = null;
	$oTempFiles =& CTempFiles::CreateInstance($oAccount);

	$oUploader = new qqFileUploader($oTempFiles,
		((bool) $oSettings->GetConf('WebMail/EnableAttachmentSizeLimit'))
			? (int) $oSettings->GetConf('WebMail/AttachmentSizeLimit') : 0);
	
	$aResult = $oUploader->handleUpload();
	$bAddImg = $oUploader->isInline();
}

echo ($bAddImg) ? '<script>parent.LoadAttachmentHandler(' : '';
echo htmlspecialchars(json_encode($aResult), ENT_NOQUOTES);
echo ($bAddImg) ? ');</script>' : '';