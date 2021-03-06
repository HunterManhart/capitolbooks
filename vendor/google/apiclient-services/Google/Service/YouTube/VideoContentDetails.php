<?php
/*
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

class Google_Service_YouTube_VideoContentDetails extends Google_Model
{
  public $caption;
  protected $contentRatingType = 'Google_Service_YouTube_ContentRating';
  protected $contentRatingDataType = '';
  protected $countryRestrictionType = 'Google_Service_YouTube_AccessPolicy';
  protected $countryRestrictionDataType = '';
  public $definition;
  public $dimension;
  public $duration;
  public $licensedContent;
  protected $regionRestrictionType = 'Google_Service_YouTube_VideoContentDetailsRegionRestriction';
  protected $regionRestrictionDataType = '';

  public function setCaption($caption)
  {
    $this->caption = $caption;
  }
  public function getCaption()
  {
    return $this->caption;
  }
  public function setContentRating(Google_Service_YouTube_ContentRating $contentRating)
  {
    $this->contentRating = $contentRating;
  }
  public function getContentRating()
  {
    return $this->contentRating;
  }
  public function setCountryRestriction(Google_Service_YouTube_AccessPolicy $countryRestriction)
  {
    $this->countryRestriction = $countryRestriction;
  }
  public function getCountryRestriction()
  {
    return $this->countryRestriction;
  }
  public function setDefinition($definition)
  {
    $this->definition = $definition;
  }
  public function getDefinition()
  {
    return $this->definition;
  }
  public function setDimension($dimension)
  {
    $this->dimension = $dimension;
  }
  public function getDimension()
  {
    return $this->dimension;
  }
  public function setDuration($duration)
  {
    $this->duration = $duration;
  }
  public function getDuration()
  {
    return $this->duration;
  }
  public function setLicensedContent($licensedContent)
  {
    $this->licensedContent = $licensedContent;
  }
  public function getLicensedContent()
  {
    return $this->licensedContent;
  }
  public function setRegionRestriction(Google_Service_YouTube_VideoContentDetailsRegionRestriction $regionRestriction)
  {
    $this->regionRestriction = $regionRestriction;
  }
  public function getRegionRestriction()
  {
    return $this->regionRestriction;
  }
}
