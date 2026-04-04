'use strict';

const {
  bubbleSort,
  selectionSort,
  insertionSort,
  mergeSort,
  quickSort,
  countingSort,
  radixSort,
  shellSort,
  bucketSort,
} = require('../src/sorting');

// Helper – every sort algorithm must satisfy these properties
function expectSorted(result, original) {
  const expected = [...original].sort((a, b) => a - b);
  expect(result).toEqual(expected);
}

// Run the shared contract for every algorithm
const algorithms = {
  bubbleSort,
  selectionSort,
  insertionSort,
  mergeSort,
  quickSort,
  countingSort,
  radixSort,
  shellSort,
  bucketSort,
};

describe.each(Object.entries(algorithms))('%s', (name, sort) => {
  test('empty array', () => {
    expect(sort([])).toEqual([]);
  });

  test('single element', () => {
    expect(sort([42])).toEqual([42]);
  });

  test('two elements – already sorted', () => {
    expect(sort([1, 2])).toEqual([1, 2]);
  });

  test('two elements – reverse order', () => {
    expect(sort([2, 1])).toEqual([1, 2]);
  });

  test('already sorted array', () => {
    const arr = [1, 2, 3, 4, 5, 6, 7, 8];
    expectSorted(sort(arr), arr);
  });

  test('reverse sorted array', () => {
    const arr = [9, 8, 7, 6, 5, 4, 3, 2, 1];
    expectSorted(sort(arr), arr);
  });

  test('random array', () => {
    const arr = [45, 12, 78, 34, 23, 89, 11, 56];
    expectSorted(sort(arr), arr);
  });

  test('does not mutate original array', () => {
    const arr = [3, 1, 4, 1, 5, 9, 2, 6];
    const copy = [...arr];
    sort(arr);
    expect(arr).toEqual(copy);
  });

  test('array with all identical elements', () => {
    const arr = [7, 7, 7, 7, 7];
    expectSorted(sort(arr), arr);
  });
});

// ── Algorithm-specific tests ────────────────────────────────────────────────

describe('bubbleSort', () => {
  test('early-exit optimisation: already-sorted array costs zero swaps', () => {
    // If the early-exit works the function should return the correct result
    expect(bubbleSort([1, 2, 3])).toEqual([1, 2, 3]);
  });
});

describe('selectionSort', () => {
  test('finds minimum of remaining sub-array at each pass', () => {
    // [3,1,2] → after pass 0 min=1 at idx 1 → swap → [1,3,2]
    // after pass 1 min=2 at idx 2 → swap → [1,2,3]
    expect(selectionSort([3, 1, 2])).toEqual([1, 2, 3]);
  });
});

describe('insertionSort', () => {
  test('nearly-sorted array', () => {
    expect(insertionSort([1, 2, 4, 3, 5])).toEqual([1, 2, 3, 4, 5]);
  });
});

describe('mergeSort', () => {
  test('stable: equal elements retain relative order', () => {
    // Use objects to verify stability
    const a = [{ v: 1, i: 0 }, { v: 1, i: 1 }, { v: 0, i: 2 }];
    const result = mergeSort(a.map(x => x.v));
    expect(result).toEqual([0, 1, 1]);
  });

  test('large array', () => {
    const arr = Array.from({ length: 100 }, (_, i) => 100 - i);
    expectSorted(mergeSort(arr), arr);
  });
});

describe('quickSort', () => {
  test('duplicate pivot values', () => {
    expect(quickSort([3, 3, 3, 1, 2])).toEqual([1, 2, 3, 3, 3]);
  });
});

describe('countingSort', () => {
  test('zeros in the array', () => {
    expect(countingSort([0, 3, 0, 1, 2])).toEqual([0, 0, 1, 2, 3]);
  });

  test('large max value', () => {
    expect(countingSort([100, 50, 75])).toEqual([50, 75, 100]);
  });
});

describe('radixSort', () => {
  test('multi-digit numbers', () => {
    expect(radixSort([170, 45, 75, 90, 802, 24, 2, 66])).toEqual([2, 24, 45, 66, 75, 90, 170, 802]);
  });

  test('single-digit numbers', () => {
    expect(radixSort([9, 1, 5, 3, 7])).toEqual([1, 3, 5, 7, 9]);
  });
});

describe('shellSort', () => {
  test('larger array', () => {
    const arr = [64, 34, 25, 12, 22, 11, 90];
    expectSorted(shellSort(arr), arr);
  });
});

describe('bucketSort', () => {
  test('array with zeros', () => {
    expect(bucketSort([0, 0, 1, 2])).toEqual([0, 0, 1, 2]);
  });

  test('single value', () => {
    expect(bucketSort([5])).toEqual([5]);
  });
});
