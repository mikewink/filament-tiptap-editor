import { Node, mergeAttributes } from "@tiptap/core";

export const GridBuilderColumn = Node.create({
  name: "gridBuilderColumn",
  content: "block+",
  gridBuilderRole: "builderColumn",
  isolating: true,
  addOptions() {
    return {
      HTMLAttributes: {
        class: "filament-tiptap-grid-builder__column",
      },
    };
  },
  addAttributes() {
    return {
      'data-col-span': {
        default: 1,
        parseHTML: (element) => element.getAttribute("data-col-span"),
        renderHTML: (attributes) => {
          return {
            'data-col-span': attributes['data-col-span'] ?? 1,
          }
        }
      },
      'style': {
        default: null,
        parseHTML: (element) => element.getAttribute("style"),
        renderHTML: (attributes) => {
          return {
            style: `grid-column: span ${attributes['data-col-span'] ?? 1};`
          }
        }
      }
    };
  },
  parseHTML() {
    return [
      {
        tag: "div",
        getAttrs: (node) => node.classList.contains("filament-tiptap-grid-builder__column") && null,
      },
    ];
  },
  renderHTML({ HTMLAttributes }) {
    return ["div", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },
});
