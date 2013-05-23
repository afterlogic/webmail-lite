<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/mime/inc_constants.php');
	require_once(WM_ROOTPATH.'common/mime/class_attachment.php');


	class AttachmentCollection extends CollectionBase
	{
		/**
		 * @access private
		 * @var string
		 */
		var $_htmlText = '';
		
		/**
		 * @param MailMessage $mailMessage
		 * @return AttachmentCollection
		 */
		function AttachmentCollection(&$mailMessage, $bIsAlternative = false)
		{
			CollectionBase::CollectionBase();
			
			if ($mailMessage != null) 
			{
				$this->_htmlText =& $mailMessage->TextBodies->HtmlTextBodyPart;
				$this->SearchAttachParts($mailMessage, $bIsAlternative);
			}
		}
		
		/**
		 * @param MimePart $mimePart
		 */
		function AddToCollection(&$_mimePart)
		{
			$_isInline = false;

			if ($this->_htmlText)
			{
				$cId = trim($_mimePart->GetContentID());
				$cLocation = trim($_mimePart->GetContentLocation());
				if (!empty($cId))
				{
					$_isInline = (false !== strpos($this->_htmlText, $cId));
				}

				if (!empty($cLocation))
				{
					$_isInline = (false !== strpos($this->_htmlText, $cLocation));
				}
			}
			
			$this->List->Add(new Attachment($_mimePart, $_isInline));
		}
		
		/**
		 * @return bool
		 */
		function HasInlineAttachment()
		{
			foreach ($this->List->Instance() as $att)
			{
				if ($att && $att->IsInline)
				{
					return true;
				}
			}
			return false;
		}		
		
		/**
		 * @return bool
		 */
		function HasNotInlineAttachment()
		{
			foreach ($this->List->Instance() as $att)
			{
				if ($att && !$att->IsInline)
				{
					return true;
				}
			}
			return false;
		}		
		
		/**
		 * @param int $index
		 * @return Attachment
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}
		
		/**
		 * @return Attachment
		 */
		function &GetLast()
		{
			return $this->List->Get($this->List->Count() - 1);
		}

		/**
		 * @return bool
		 */
		function DeleteLast()
		{
			return $this->List->RemoveAt($this->List->Count() - 1);
		}
		
		/**
		 * @param MimePart $mimePart
		 */
		function SearchAttachParts(&$mimePart, $bIsAlternative = false)
		{
			if ($mimePart->_subParts == null)
			{
				if ($mimePart->IsMimePartAttachment($bIsAlternative))
				{
					$this->AddToCollection($mimePart);
				}
			}
			else
			{
				for ($i = 0, $c = $mimePart->_subParts->List->Count(); $i < $c; $i++)
				{
					$subPart =& $mimePart->_subParts->List->Get($i);
					$this->SearchAttachParts($subPart, $bIsAlternative);
					unset($subPart);
				}
			}
		}
		
		/**
		 * @return bool
		 */
		function AddFromFile($filepath, $attachname, $mimetype, $isInline = false)
		{
			$data = '';
			$handle = @fopen($filepath, 'rb');
			if ($handle)
			{
				$size = @filesize($filepath);
				$data = ($size > 0) ? @fread($handle, $size) : '';
				@fclose($handle);
			}
			else 
			{
				setGlobalError(' can\'t open '.$filepath);
				return false;
			}
		
			if ($this->AddFromBinaryBody($data, $attachname, $mimetype, $isInline))
			{
				return true;
			}
			return false;
			
		}
		
		function AddFromBinaryBody($bbody, $attachname, $mimetype, $isInline)
		{
			if (false !== $bbody && null !== $bbody)
			{
				$AttachType = ($isInline) ? MIMEConst_InlineLower : MIMEConst_AttachmentLower;
				
				$attachname = ConvertUtils::EncodeHeaderString($attachname, $GLOBALS[MailInputCharset], 'utf-8');
				
				$mimePart = new MimePart();
				$mimePart->Headers->SetHeaderByName(MIMEConst_ContentType, $mimetype.';'.CRLF."\t".MIMEConst_NameLower.'="'.$attachname.'"', false);
				$mimePart->Headers->SetHeaderByName(MIMEConst_ContentTransferEncoding, MIMEConst_Base64Lower, false);			
				$mimePart->Headers->SetHeaderByName(MIMEConst_ContentDisposition, $AttachType.';'.CRLF."\t".MIMEConst_FilenameLower.'="'.$attachname.'"', false);

				$mimePart->_body = ConvertUtils::base64WithLinebreak($bbody);
				
				$this->List->Add(new Attachment($mimePart, $isInline));
				return true;
			}
			return false;
		}

		function AddFromBodyStructure($attachname, $idx, $size, $encode, $duration = null, $contentId = null, $bIsInline = false)
		{
			$attachname = ConvertUtils::EncodeHeaderString($attachname, $GLOBALS[MailInputCharset], 'utf-8');
			$mimetype = ConvertUtils::GetContentTypeFromFileName($attachname);
			
			$AttachType = ($bIsInline) ? MIMEConst_InlineLower : MIMEConst_AttachmentLower;
			
			$mimePart = new MimePart();
			$mimePart->BodyStructureIndex = $idx;
			$mimePart->BodyStructureSize = $size;
			$mimePart->BodyStructureEncode = $encode;
			$mimePart->BodyStructureDuration = (null !== $duration && 0 < $duration) ? $duration : null;
			$mimePart->Headers->SetHeaderByName(MIMEConst_ContentType, $mimetype.';'.CRLF."\t".MIMEConst_NameLower.'="'.$attachname.'"', false);
			$mimePart->Headers->SetHeaderByName(MIMEConst_ContentTransferEncoding, MIMEConst_Base64Lower, false);
			$mimePart->Headers->SetHeaderByName(MIMEConst_ContentDisposition, $AttachType.';'.CRLF."\t".MIMEConst_FilenameLower.'="'.$attachname.'"', false);
			if (null !== $contentId)
			{
				$mimePart->Headers->SetHeaderByName(MIMEConst_ContentID, '<'.$contentId.'>', false);
			}

			$this->List->Add(new Attachment($mimePart, $bIsInline));
			
			return true;
		}
	}