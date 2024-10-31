<?php
/**
 * Class SN_OT_CUSTOMER_SHIPPED_ORDER_MAIL file.
 *
 * @package WooCommerce\Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


/**
 * Customer Shipped Order Email.
 *
 * Order shipped emails are sent to the customer when the order is marked shipped and usual indicates that the order has been shipped.
 *
 * @class       SN_OT_CUSTOMER_SHIPPED_ORDER
 * @version     2.0.0
 * @extends     WC_Email
 */
class SN_OT_CUSTOMER_SHIPPED_ORDER_MAIL extends WC_Email {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id             = 'sn_ot_shipped_order_mail';
        $this->customer_email = true;
        $this->title          = __( 'Shipped order', 'woocommerce' );
        $this->description    = __( 'Order shipped emails are sent to customers when their orders are marked shipped.', 'woocommerce' );
        $this->template_base    = SN_OT_TEMPLATE_PATH . '/emails/';
        $this->template_html  = 'customer-shipped-order.php';
        $this->template_plain = 'plain/customer-shipped-order.php';
        $this->placeholders   = array(
            '{site_title}'   => $this->get_blogname(),
            '{order_date}'   => '',
            '{order_number}' => '',
        );

        // Triggers for this email.
        add_action( 'sn_ot_send_shipped_order_mail', array( $this, 'trigger' ), 10, 2 );

        // Call parent constructor.
        parent::__construct();
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int            $order_id The order ID.
     * @param WC_Order|false $order Order object.
     */
    public function trigger( $order_id, $order = false ) {
        $this->setup_locale();

        if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
            $order = wc_get_order( $order_id );
        }

        if ( is_a( $order, 'WC_Order' ) ) {
            $this->object                         = $order;
            $this->recipient                      = $this->object->get_billing_email();
            $this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
            $this->placeholders['{order_number}'] = $this->object->get_order_number();
        }

        if ( $this->is_enabled() && $this->get_recipient() ) {
            $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        }

        $this->restore_locale();
    }

    /**
     * Get email subject.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_subject() {
        return __( 'Your {site_title} order has been shipped', 'woocommerce' );
    }

    /**
     * Get email heading.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_heading() {
        return __( 'Your order has been shipped', 'woocommerce' );
    }

    /**
     * Get content html.
     *
     * @return string
     */
    public function get_content_html() {

        $args = apply_filters( 'sn_ot_customer_shipped_order_args', array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading(),
            'email_content' => $this->format_string($this->get_option('mail_content')),
            'email'         => $this
        ));

        ob_start();
        wc_get_template($this->template_html, $args, false, $this->template_base);
        return ob_get_clean();
    }

    /**
     * Get content plain.
     *
     * @return string
     */
    public function get_content_plain() {
        $args = apply_filters( 'sn_ot_customer_shipped_order_args', array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading(),
            'email_content' => $this->format_string($this->get_option('mail_content')),
            'plain_text'    => true,
            'email'         => $this
        ));

        ob_start();
        wc_get_template($this->template_html, $args, false, $this->template_base);
        return ob_get_clean();
    }

    /**
     * Initialise settings form fields.
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled'    => array(
                'title'   => __( 'Enable/Disable', 'woocommerce' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable this email notification', 'woocommerce' ),
                'default' => 'yes',
            ),
            'subject'    => array(
                'title'       => __( 'Subject', 'woocommerce' ),
                'type'        => 'text',
                'desc_tip'    => true,
                /* translators: %s: list of placeholders */
                'description' => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>{site_title}, {order_date}, {order_number}</code>' ),
                'placeholder' => $this->get_default_subject(),
                'default'     => '',
            ),
            'heading'    => array(
                'title'       => __( 'Email heading', 'woocommerce' ),
                'type'        => 'text',
                'desc_tip'    => true,
                /* translators: %s: list of placeholders */
                'description' => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>{site_title}, {order_date}, {order_number}</code>' ),
                'placeholder' => $this->get_default_heading(),
                'default'     => '',
            ),
            'email_type' => array(
                'title'       => __( 'Email type', 'woocommerce' ),
                'type'        => 'select',
                'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
                'default'     => 'html',
                'class'       => 'email_type wc-enhanced-select',
                'options'     => $this->get_email_type_options(),
                'desc_tip'    => true,
            ),
        );
    }
}


return new SN_OT_CUSTOMER_SHIPPED_ORDER_MAIL();
