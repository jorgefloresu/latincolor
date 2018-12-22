<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        public function get_news($slug = FALSE)
		{
			if ($slug === FALSE)
			{
					$query = $this->db->get('news');
					return $query->result_array();
			}

			$query = $this->db->get_where('news', array('slug' => $slug));
			return $query->row_array();
		}
		
		public function set_news($uploadData)
		{
			$this->load->helper('url');

			$slug = url_title($this->input->post('title'), 'dash', TRUE);

			$data = array(
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'text' => $this->input->post('text'),
				'img_name' => $uploadData['raw_name'],
				'thumb_name' => $uploadData['raw_name']. '_thumb' .$uploadData['file_ext'],
				'ext' => $uploadData['file_ext'],
				'upload_date' => time()
			);

			return $this->db->insert('news', $data);
		}
		
		public function upd_news($uploadData = NULL)
		{
			$this->load->helper('url');

			$slug = url_title($this->input->post('title'), 'dash', TRUE);
			
			if ($uploadData === NULL)
			{
				$data = array(
					'title' => $this->input->post('title'),
					'slug' => $slug,
					'text' => $this->input->post('text'),
				);

			}
			else
			{
				$data = array(
					'title' => $this->input->post('title'),
					'slug' => $slug,
					'text' => $this->input->post('text'),
					'img_name' => $uploadData['raw_name'],
					'thumb_name' => $uploadData['raw_name'] . (empty($uploadData['raw_name']) ? '' : '_thumb') .$uploadData['file_ext'],
					'ext' => $uploadData['file_ext'],
					'upload_date' => time()
				);
			}
			$this->db->where('id', $this->input->post('id'));
			return $this->db->update('news', $data);
			
		}
		
		public function clear_image_data()
		{
			$data = array(
					'img_name' => '',
					'thumb_name' => '',
					'ext' => '',
					'upload_date' => time()
			);

			$this->db->where('id', $this->input->post('id'));
			return $this->db->update('news', $data);

		}
		
}