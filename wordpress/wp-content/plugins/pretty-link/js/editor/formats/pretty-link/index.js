/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { withSpokenMessages } from "@wordpress/components";
import { Component, Fragment } from "@wordpress/element";
import {
  getTextContent,
  applyFormat,
  removeFormat,
  slice,
  registerFormatType
} from "@wordpress/rich-text";
import { isURL, isEmail } from "@wordpress/url";
import {
  RichTextToolbarButton,
  RichTextShortcut
} from "@wordpress/block-editor";
import InlineLinkUI from "../../components/link-editor";

/**
 * Block constants
 */
const name = "pretty-link/pretty-link";
const title = __("Pretty Link");

export const prettyLink = {
  name,
  title,
  tagName: "a",
  className: "pretty-link",
  attributes: {
    url: "href",
    target: "target"
  },
  edit: withSpokenMessages(
    class LinkEdit extends Component {
      constructor() {
        super(...arguments);

        this.addLink = this.addLink.bind(this);
        this.stopAddingLink = this.stopAddingLink.bind(this);
        this.onRemoveFormat = this.onRemoveFormat.bind(this);
        this.state = {
          addingLink: false
        };
      }

      addLink() {
        const { value, onChange } = this.props;
        const text = getTextContent(slice(value));

        if (text && isURL(text)) {
          onChange(
            applyFormat(value, { type: name, attributes: { url: text } })
          );
        } else {
          this.setState({ addingLink: true });
        }
      }

      stopAddingLink() {
        this.setState({ addingLink: false });
      }

      onRemoveFormat() {
        const { value, onChange, speak } = this.props;

        onChange(removeFormat(value, name));
        speak(__("Link removed."), "assertive");
      }

      render() {
        const { isActive, activeAttributes, value, onChange, contentRef } = this.props;

        return (
          <>
            <RichTextShortcut
              type="primary"
              character="p"
              onUse={this.addLink}
            />
            <RichTextShortcut
              type="primaryShift"
              character="p"
              onUse={this.onRemoveFormat}
            />
            {isActive && (
              <RichTextToolbarButton
                icon="star-filled"
                title={__("Unlink")}
                onClick={this.onRemoveFormat}
                isActive={isActive}
                shortcutType="primaryShift"
                shortcutCharacter="p"
              />
            )}
            {!isActive && (
              <RichTextToolbarButton
                icon="star-filled"
                title={title}
                onClick={this.addLink}
                isActive={isActive}
                shortcutType="primary"
                shortcutCharacter="p"
              />
            )}
            <InlineLinkUI
              addingLink={this.state.addingLink}
              stopAddingLink={this.stopAddingLink}
              isActive={isActive}
              activeAttributes={activeAttributes}
              value={value}
              onChange={onChange}
              contentRef={contentRef}
            />
          </>
        );
      }
    }
  )
};

function registerFormats() {
  [prettyLink].forEach(({ name, ...settings }) =>
    registerFormatType(name, settings)
  );
}
registerFormats();
