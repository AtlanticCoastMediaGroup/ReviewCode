<?php
class Contact_Form_Contact extends Zend_Form
{
	public function init()
	{
		// create new element
		$name = $this->createElement('text', 'name');
		// element options
		$name->setLabel('Enter your name:');
		$name->setRequired(TRUE);
		$name->setAttrib('size',40);
		// add the element to the form
		$this->addElement($name);
		// create new element
		$email = $this->createElement('text', 'email');
		// element options
		$email->setLabel('Enter your email address:');
		$email->setRequired(TRUE);
		$email->setAttrib('size',40);
		$email->addValidator('EmailAddress');
		$email->addErrorMessage('Invalid email address!');
		// add the element to the form
		$this->addElement($email);
		// create new element
		$subject = $this->createElement('text', 'subject');
		// element options
		$subject->setLabel('Subject: ');
		$subject->setRequired(TRUE);
		$subject->setAttrib('size',60);
		// add the element to the form
		$this->addElement($subject);

		$attachment = $this->createElement('file', 'attachment');
		$attachment->setLabel('Attach a file:');
		$attachment->setRequired(FALSE);
		$attachment->setDestination(APPLICATION_PATH.'/../uploads');
		$attachment->addValidator('Count', false, 1);
		$attachment->addValidator('Size', false, 102400);
		$attachment->addValidator('Extension', false, 'jpg,png,gif');
		$this->addElement($attachment);
		
		// create new element
		$this->setAttrib('enctype', 'multipart/form-data');

		$message = $this->createElement('textarea', 'message');
		// element options
		$message->setLabel('Message:');
		$message->setRequired(TRUE);
		$message->setAttrib('cols',50);
		$message->setAttrib('rows',12);
		// add the element to the form
		$this->addElement($message);

		$privateKey = '6LdSgbsSAAAAAHC4D0nPtq1RxjFtO2Nnpy0b6Q4h';
		$publicKey = '6LdSgbsSAAAAAH_90hF4HAsSaX37K3sKJzGmoNry';
		$recaptcha = new Zend_Service_ReCaptcha($publicKey, $privateKey);
		$captcha = new Zend_Form_Element_Captcha('captcha', array(
			'captcha' => 'ReCaptcha', 
			'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha)
		));
		$this->addElement($captcha);

		$submit = $this->addElement('submit', 'submit', array('label' => 'Send Message'));

	}
}
