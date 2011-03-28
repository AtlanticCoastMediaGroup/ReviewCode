<?php
class Contact_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$frmContact = new Contact_Form_Contact();
		if ($this->_request->isPost() && $frmContact->isValid($_POST)) {
			//send the message
			$sender = $frmContact->getValue('name');
			$email = $frmContact->getValue('email');
			$subject = $frmContact->getValue('subject');
			$message = $frmContact->getValue('message');
			
			$htmlMessage = $this->view->partial('templates/default.phtml', $frmContact->getValues());
			$mail = new Zend_Mail();
			$config = array('auth' => 'login', 'username' => 'amitg@endroittechnologies.com', 'password' => 'amitg123');
			$transport = new Zend_Mail_Transport_Smtp('mail.endroittechnologies.com', $config);
			$mail->setSubject($subject);
			$mail->setFrom($email, $sender);
			$mail->addTo('amitg@endroittechnologies.com', 'webmaster');
			$fileControl = $frmContact->getElement('attachment');
			if ($fileControl->isUploaded()) {
				$attachmentName = $fileControl->getFileName();
				$fileStream = file_get_contents($attachmentName);
				$attachment = $mail->createAttachment($fileStream);
				$attachment->filename = basename($attachmentName);
			}
			$mail->setBodyHtml($htmlMessage);
			$mail->setBodyText($message);
			$result = $mail->send($transport);
			$this->view->messageProcessed = true;
			if ($result) {
				$this->view->sendError = false;
			} else {
				$this->view->sendError = true;
			}
		}
		$frmContact->setAction(APPLICATION_URL.'/public/contact');
		$frmContact->setMethod('post');
		$this->view->form = $frmContact;
	}
}
