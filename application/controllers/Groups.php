<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CI_Capstone_Controller
{


        private $page_;
        private $limit;

        function __construct()
        {
                parent::__construct();
                $this->lang->load('ci_capstone/ci_excel');
                $this->load->model('Group_model');
                $this->load->library('pagination');

                /**
                 * pagination limit
                 */
                $this->limit = 10;

                /**
                 * get the page from url
                 * 
                 * if has not, default $page will is 1
                 */
                $this->page_ = get_page_in_url();
                $this->breadcrumbs->unshift(2, lang('administrators_label'), '#');
                $this->breadcrumbs->unshift(3, lang('index_groups_th'), 'groups');
        }

        /**
         * @author Lloric Mayuga Garcia <emorickfighter@gmail.com>
         */
        public function index()
        {

                // set the flash data error message if there is one
                // $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                //list the users
                $group_obj = $this->Group_model->
                        limit($this->limit, $this->limit * $this->page_ - $this->limit)->
                        //order_by('updated_at', 'DESC')->
                        //order_by('created_at', 'DESC')->
                        set_cache('groups_page_' . $this->page_)->
                        get_all();

                /**
                 * where data array from db stored
                 */
                $table_data = array();
                /**
                 * check if has a result
                 * 
                 * sometime pagination can replace a page that has no value by crazy users :)
                 */
                if ($group_obj)
                {
                        foreach ($group_obj as $group)
                        {

                                array_push($table_data, array(
                                    my_htmlspecialchars($group->name),
                                    my_htmlspecialchars($group->description),
                                ));
                        }
                }
                /*
                 * preparing html table
                 */
                /*
                 * header
                 */
                $header = array(
                    lang('index_groups_th'),
                    lang('index_groups_th')
                );

                $pagination = $this->pagination->generate_bootstrap_link('admin/groups/index', $this->Group_model->count_rows() / $this->limit);

                $template['table_groups'] = $this->table_bootstrap($header, $table_data, 'table_open_bordered', 'index_groups_th', $pagination, TRUE);
                $template['message']      = (($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                $template['bootstrap']    = $this->_bootstrap();
                /**
                 * rendering users view
                 */
                $this->render('admin/groups', $template);
        }

        /**
         * 
         * @return array
         *  @author Lloric Garcia <emorickfighter@gmail.com>
         */
        private function _bootstrap()
        {
                /**
                 * for header
                 * 
                 */
                $header       = array(
                    'css' => array(
                        'css/bootstrap.min.css',
                        'css/bootstrap-responsive.min.css',
                        'css/uniform.css',
                        'css/select2.css',
                        'css/matrix-style.css',
                        'css/matrix-media.css',
                        'font-awesome/css/font-awesome.css',
                        'http://fonts.googleapis.com/css?family=Open+Sans:400,700,800',
                    ),
                    'js'  => array(
                    ),
                );
                /**
                 * for footer
                 * 
                 */
                $footer       = array(
                    'css' => array(
                    ),
                    'js'  => array(
                        'js/jquery.min.js',
                        'js/jquery.ui.custom.js',
                        'js/bootstrap.min.js',
                        'js/jquery.uniform.js',
                        'js/select2.min.js',
                        'js/jquery.dataTables.min.js',
                        'js/matrix.js',
                        'js/matrix.tables.js',
                    ),
                );
                /**
                 * footer extra
                 */
                $footer_extra = '';
                return generate_link_script_tag($header, $footer, $footer_extra);
        }

}
