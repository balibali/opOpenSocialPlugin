<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * application actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara<kawahara@tejimaya.net>
 */
class opOpenSocialPluginActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    return $this->redirect('opOpenSocialPlugin/applicationConfig');
  }

  /**
   * Executes applicationConfig action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeApplicationConfig(sfWebRequest $request)
  {
    $this->applicationConfigForm = new ApplicationConfigForm();

    if ($request->isMethod(sfRequest::POST))
    {
      $this->applicationConfigForm->bind($request->getParameter('application_config'));
      if ($this->applicationConfigForm->isValid())
      {
        $this->applicationConfigForm->save();
      }
    }
    return sfView::SUCCESS;
  }

  /**
   * Executes containerConfig action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeContainerConfig(sfWebRequest $request)
  {
    $this->containerConfigForm = new ContainerConfigForm();

    if ($request->isMethod(sfRequest::POST))
    {
      $this->containerConfigForm->bind($request->getParameter('container_config'));
      if ($this->containerConfigForm->isValid())
      {
        $this->containerConfigForm->save();
      }
    }

    $this->isUseOuterShindig = SnsConfigPeer::get('is_use_outer_shindig');
    return sfView::SUCCESS;
  }

  /**
   * Executes list action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeList(sfWebRequest $request)
  {
    $criteria = new Criteria();
    $criteria->addDescendingOrderByColumn(ApplicationPeer::ID);
    $this->pager = new sfPropelPager('Application', 20);
    $this->pager->setCriteria($criteria);
    $this->pager->setPage($request->getParameter('page',1));
    $this->pager->init();

    $this->addform = new AddApplicationForm();
    if ($request->isMethod(sfRequest::POST))
    {
      $this->addform->bind($request->getParameter('contact'));
      if ($this->addform->isValid())
      {
        $contact = $this->addform->getValues();
        try
        {
          $application = ApplicationPeer::addApplication($contact['application_url'], $this->getUser()->getCulture(),true);
        }
        catch (Exception $e)
        {
          return sfView::ERROR;
        }
      }
      else
      {
        return sfView::SUCCESS;
      }
    }
    else
    {
      return sfView::SUCCESS;
    }

    return $this->redirect('opOpenSocialPlugin/info?id='.$application->getId());
  }

  /**
   * Executes info action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeInfo(sfWebRequest $request)
  {
    $application = ApplicationPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($application);
    $this->application = $application;
    return sfView::SUCCESS;
  }

  /**
   * Executes profileSetting action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeProfileSetting(sfWebRequest $request)
  {
    $this->profileConfigForm = new OpenSocialPersonFieldConfigForm();
    
    if ($request->isMethod(sfRequest::POST))
    {
      $this->profileConfigForm->bind($request->getParameter('opensocial_person_field_config'));
      if ($this->profileConfigForm->isValid())
      {
        $this->profileConfigForm->save();
      }
    }
    
    return sfView::SUCCESS;
  }

  /**
   * Executes deleteApplication action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeDeleteApplication(sfWebRequest $request)
  {
    $application = ApplicationPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($application);

    if ($request->isMethod(sfRequest::POST))
    {
      $request->checkCSRFProtection();
      $application->delete();
      return $this->redirect('opOpenSocialPlugin/list');
    }

    $this->form = new sfForm();

    return sfView::SUCCESS;
  }

  /**
   * Executes updateApplication action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeUpdateApplication(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $application = ApplicationPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($application);

    ApplicationPeer::updateApplication($request->getParameter('id'), $this->getUser()->getCulture());

    $this->redirect('opOpenSocialPlugin/info?id='.$application->getId());
  }

  /**
   * Executes generate container config
   *
   * @param sfWebRequest $request A request object
   */
  public function executeGenerateContainerConfig(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug',false);
    $response = $this->getResponse();
    $response->setContentType('text/javascript');
    $response->setHttpHeader('Content-Disposition','attachment; filename="openpne.js');
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig(false);
    $this->json = $opOpenSocialContainerConfig->generate();
    return sfView::SUCCESS;
  }
}
