import {
    CheckboxControl,
    TextControl,
    TextareaControl,
} from '@wordpress/components';
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

        const EmbeddableOptionsPanel = () => {
            return (
                <PluginDocumentSettingPanel
                    name="wp-embeddable-options-sidebar"
                    title="Embeddable Options"
                    icon={() => ''}
                >
                    <DisableWpHeadField
                        label="Disable wp_head()"
                        help="Disable scripts and styles from the page header"
                        metaFieldName="_wp_embeddable_disable_wp_head"
                    />
                    <DisableWpFooterField
                        label="Disable wp_footer()"
                        help="Disable scripts and styles from the page footer"
                        metaFieldName="_wp_embeddable_disable_wp_footer"
                    />
                </PluginDocumentSettingPanel>
            );
        };

        registerPlugin('wp-embeddable-options-sidebar', {
            render: EmbeddableOptionsPanel,
        });

        const ShortCodeField = compose(
            withSelect(select => {
                const id = select('core/editor').getEditedPostAttribute('id');
                return {
                    value: `[embeddable ${id} autosize]`,
                };
            })
        )(TextControl);

        const EmbedCodeField = compose(
            withSelect(select => {
                const link = select('core/editor').getEditedPostAttribute(
                    'link'
                );
                return {
                    value: makeEmbedCode(link),
                };
            })
        )(TextareaControl);

        const EmbeddableUsagePanel = () => {
            return (
                <PluginDocumentSettingPanel
                    name="wp-embeddable-usage-sidebar"
                    title="Embeddable Usage"
                    icon={() => ''}
                >
                    <ShortCodeField label="Shortcode" readOnly />
                    <EmbedCodeField label="Embed code" readOnly />
                </PluginDocumentSettingPanel>
            );
        };

        registerPlugin('wp-embeddable-usage-sidebar', {
            render: EmbeddableUsagePanel,
        });
    }
});

function makeEmbedCode(permalink) {
    return (
        `<iframe src="${permalink}"` +
        ' width="100%"' +
        ' height="360px"' +
        ' frameborder="0"' +
        ' allowfullscreen' +
        '></iframe>'
    );
}
