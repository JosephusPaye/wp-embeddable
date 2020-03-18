import { CheckboxControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { select, withSelect, withDispatch } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import domReady from '@wordpress/dom-ready';

domReady(() => {
    const postType = select('core/editor').getCurrentPostType();

    if (postType === 'embeddable') {
        const mapSelectToProps = (select, props) => {
            return {
                metaFieldValue: select('core/editor').getEditedPostAttribute(
                    'meta'
                )[props.metaFieldName],
            };
        };

        const mapDispatchToProps = (dispatch, props) => {
            return {
                setMetaFieldValue: function(value) {
                    dispatch('core/editor').editPost({
                        meta: { [props.metaFieldName]: value },
                    });
                },
            };
        };

        const CheckboxField = props => {
            return (
                <CheckboxControl
                    label={props.label}
                    help={props.help}
                    checked={props.metaFieldValue}
                    onChange={value => {
                        props.setMetaFieldValue(value);
                    }}
                />
            );
        };

        const DisableWpHeadField = compose(
            withDispatch(mapDispatchToProps),
            withSelect(mapSelectToProps)
        )(CheckboxField);

        const DisableWpFooterField = compose(
            withDispatch(mapDispatchToProps),
            withSelect(mapSelectToProps)
        )(CheckboxField);

        const SidebarPanel = () => {
            return (
                <PluginDocumentSettingPanel
                    name="wp-embeddable-sidebar"
                    title="Embeddable Options"
                    icon={() => ''}
                >
                    <DisableWpHeadField
                        label="Disable wp_head()"
                        help="Disable scripts and styles from the page header"
                        metaFieldName="wp_embeddable_disable_wp_head"
                    />
                    <DisableWpFooterField
                        label="Disable wp_footer()"
                        help="Disable scripts and styles from the page footer"
                        metaFieldName="wp_embeddable_disable_wp_footer"
                    />
                </PluginDocumentSettingPanel>
            );
        };

        registerPlugin('wp-embeddable-sidebar', {
            render: SidebarPanel,
        });
    }
});
