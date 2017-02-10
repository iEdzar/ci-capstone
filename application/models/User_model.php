<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends MY_Model
{

        public function __construct()
        {
                $this->table                        = 'users';
                $this->primary_key                  = 'id';
                $this->has_one['students_subjects'] = array(
                    'foreign_model' => 'Students_subjects_model',
                    'foreign_table' => 'students_subjects',
                    'foreign_key'   => 'user_id',
                    'local_key'     => 'id'
                );
                $this->has_one['subject_offers'] = array(
                    'foreign_model' => 'Subject_offer_model',
                    'foreign_table' => 'subject_offers',
                    'foreign_key'   => 'subject_offer_id',
                    'local_key'     => 'subject_offer_id'
                );

                $this->timestamps        = TRUE;
                $this->return_as         = 'object';
                $this->timestamps_format = 'timestamp';
                parent::__construct();
        }

}
