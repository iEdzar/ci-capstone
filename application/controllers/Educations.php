<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Educations extends CI_Capstone_Controller
{


        private $page_;
        private $limit;

        function __construct()
        {
                parent::__construct();
                show_404();
                $this->load->model('Education_model');
                $this->load->library('pagination');
                /**
                 * @Contributor: Jinkee Po <pojinkee1@gmail.com>
                 *         
                 */
                /**
                 * pagination limit
                 */
                $this->limit = 10;

                /**
                 * get the page from url
                 * 
                 */
                $this->page_ = get_page_in_url();
                $this->breadcrumbs->unshift(2, lang('index_utility_label'), '#');
                $this->breadcrumbs->unshift(3, lang('index_education_heading'), 'educations');
        }

        public function index()
        {


                $education_obj = $this->Education_model->
                        with_user_created('fields:first_name,last_name')->
                        with_user_updated('fields:first_name,last_name')->
                        limit($this->limit, $this->limit * $this->page_ - $this->limit)->
                        order_by('updated_at', 'DESC')->
                        order_by('created_at', 'DESC')->
                        set_cache('educations_page_' . $this->page_)->
                        get_all();


                $table_data = array();

                if ($education_obj)
                {

                        foreach ($education_obj as $education)
                        {

                                $tmp = array(
                                    my_htmlspecialchars($education->education_code),
                                    my_htmlspecialchars($education->education_description),
                                );
                                if ($this->ion_auth->is_admin())
                                {
                                        $tmp[] = $this->User_model->modidy($education, 'created');
                                        $tmp[] = $this->User_model->modidy($education, 'updated');
                                }
                                $table_data[] = $tmp;
                        }
                }

                /*
                 * Table headers
                 */
                $header = array(
                    lang('index_education_code_th'),
                    lang('index_education_description_th'),
                );
                if ($this->ion_auth->is_admin())
                {
                        $header[] = 'Created By';
                        $header[] = 'Updated By';
                }
                $pagination = $this->pagination->generate_bootstrap_link('educations/index', $this->Education_model->count_rows() / $this->limit);

                $template['table_educations'] = $this->table_bootstrap($header, $table_data, 'table_open_bordered', 'index_education_heading', $pagination, TRUE);
                $template['message']          = (($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                $template['bootstrap']        = $this->_bootstrap();
                /**

                  /**
                 * rendering users view
                 */
                $this->render('admin/educations', $template);
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
