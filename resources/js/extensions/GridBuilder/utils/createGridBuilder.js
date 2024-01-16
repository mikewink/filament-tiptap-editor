import { createBuilderColumn } from "./createBuilderColumn";
import { getGridBuilderNodeTypes } from "./getGridBuilderNodeTypes";

export function createGridBuilder(schema, colsCount, type, stackAt, leftSpan, rightSpan, colContent = null) {
  const { gridBuilder, builderColumn } = getGridBuilderNodeTypes(schema);
  const cols = [];

  if (type === 'asymmetric') {
    cols.push(createBuilderColumn(builderColumn, leftSpan, colContent));
    cols.push(createBuilderColumn(builderColumn, rightSpan, colContent));
  } else {
    for (let index = 0; index < colsCount; index += 1) {
      const col = createBuilderColumn(builderColumn, 1, colContent);

      if (col) {
        cols.push(col);
      }
    }
  }

  return gridBuilder.createChecked({ 'data-cols': colsCount, 'data-type': type, 'data-stack-at': stackAt }, cols);
}
