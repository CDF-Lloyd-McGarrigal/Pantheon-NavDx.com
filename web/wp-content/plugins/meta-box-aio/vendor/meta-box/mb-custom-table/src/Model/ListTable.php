<?php
namespace MetaBox\CustomTable\Model;

class ListTable extends \WP_List_Table {
	private $base_url;
	private $model;
	private $table;

	public function __construct( $args ) {
		$this->model    = $args['model'];
		$this->table    = $this->model->table;
		$this->base_url = admin_url( "admin.php?page=model-{$this->model->name}" );

		parent::__construct( [
			'singular' => $this->model->labels['singular_name'],
			'plural'   => $this->model->labels['name'],
		] );
	}

	public function prepare_items() {
		global $wpdb;

		$this->_column_headers = $this->get_column_info();

		$per_page = $this->get_items_per_page( "{$this->model->name}_per_page", 20 );
		$page     = $this->get_pagenum();

		$this->set_pagination_args( [
			'total_items' => $this->get_total_items(),
			'per_page'    => $per_page,
		] );

		$where = apply_filters( "mbct_{$this->model->name}_query_where", '' );
		$order = apply_filters( "mbct_{$this->model->name}_query_order", '' );

		$limit  = "LIMIT $per_page";
		$offset = ' OFFSET ' . ( $page - 1 ) * $per_page;
		$sql    = "SELECT * FROM $this->table $where $order $limit $offset";

		$this->items = $wpdb->get_results( $sql, 'ARRAY_A' );
	}

	private function get_total_items() {
		global $wpdb;
		return $wpdb->get_var( "SELECT COUNT(*) FROM $this->table" );
	}

	public function get_columns() {
		$columns = [
			'cb' => '<input type="checkbox">',
			'id' => __( 'ID', 'mb-custom-table' ),
		];

		return apply_filters( "mbct_{$this->model->name}_columns", $columns );
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="items[]" value="%s">',
			intval( $item['ID'] )
		);
	}

	public function column_id( $item ) {
		$title = sprintf(
			'<a href="%s"><strong>#%d</strong></a>',
			add_query_arg( [
				'model-action' => 'edit',
				'model-id'     => $item['ID'],
			], $this->base_url ),
			$item['ID']
		);
		return $title . $this->row_actions( $this->get_row_actions( $item ) );
	}

	public function column_default( $item, $column_name ) {
		$output = $item[ $column_name ] ?? '';

		return apply_filters( "mbct_{$this->model->name}_column_output", $output, $column_name, $item, $this->model );
	}

	public function get_sortable_columns() {
		return apply_filters( "mbct_{$this->model->name}_sortable_columns", [] );
	}

	protected function get_row_actions( $item ) {
		$actions = [
			'edit' => sprintf(
				'<a href="%s">' . esc_html__( 'Edit', 'mb-custom-table' ) . '</a>',
				add_query_arg( [
					'model-action' => 'edit',
					'model-id'     => $item['ID'],
				], $this->base_url )
			),
			'delete' => sprintf(
				'<a href="#" data-id="%d">' . esc_html__( 'Delete', 'mb-custom-table' ) . '</a>',
				$item['ID'],
				$this->model->name
			)
		];
		return $actions;
	}

	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => __( 'Delete', 'mb-custom-table' ),
		];

		return $actions;
	}
}
