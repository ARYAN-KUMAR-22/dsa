'use strict';

const {
  binarySearch,
  firstOccurrence,
  lowerBound,
  maxSubarrayDivideConquer,
  kWayMerge,
} = require('../src/divideAndConquer');

// ── binarySearch ────────────────────────────────────────────────────────────

describe('binarySearch', () => {
  const arr = [1, 3, 5, 7, 9, 11, 13];

  test('finds element at the beginning', () => {
    expect(binarySearch(arr, 1)).toBe(0);
  });

  test('finds element in the middle', () => {
    expect(binarySearch(arr, 7)).toBe(3);
  });

  test('finds element at the end', () => {
    expect(binarySearch(arr, 13)).toBe(6);
  });

  test('returns -1 for missing target smaller than all', () => {
    expect(binarySearch(arr, 0)).toBe(-1);
  });

  test('returns -1 for missing target larger than all', () => {
    expect(binarySearch(arr, 20)).toBe(-1);
  });

  test('returns -1 for a gap in the middle', () => {
    expect(binarySearch(arr, 6)).toBe(-1);
  });

  test('empty array returns -1', () => {
    expect(binarySearch([], 5)).toBe(-1);
  });

  test('single-element array – found', () => {
    expect(binarySearch([42], 42)).toBe(0);
  });

  test('single-element array – not found', () => {
    expect(binarySearch([42], 7)).toBe(-1);
  });

  test('two-element array – target is first', () => {
    expect(binarySearch([2, 5], 2)).toBe(0);
  });

  test('two-element array – target is second', () => {
    expect(binarySearch([2, 5], 5)).toBe(1);
  });

  test('large sorted array', () => {
    const big = Array.from({ length: 1000 }, (_, i) => i * 2);
    expect(binarySearch(big, 998)).toBe(499);
    expect(binarySearch(big, 999)).toBe(-1);
  });
});

// ── firstOccurrence ─────────────────────────────────────────────────────────

describe('firstOccurrence', () => {
  test('no duplicates', () => {
    expect(firstOccurrence([1, 2, 3, 4, 5], 3)).toBe(2);
  });

  test('duplicates at the start', () => {
    expect(firstOccurrence([1, 1, 1, 2, 3], 1)).toBe(0);
  });

  test('duplicates in the middle', () => {
    expect(firstOccurrence([1, 2, 2, 2, 3], 2)).toBe(1);
  });

  test('duplicates at the end', () => {
    expect(firstOccurrence([1, 2, 3, 3, 3], 3)).toBe(2);
  });

  test('target absent', () => {
    expect(firstOccurrence([1, 2, 3], 5)).toBe(-1);
  });

  test('empty array', () => {
    expect(firstOccurrence([], 1)).toBe(-1);
  });

  test('target absent but value larger than mid exists – hits high=mid−1 branch', () => {
    // [1,3,5,7,9], target=4 → mid=2 (val=5 > 4) → line 36 covered
    expect(firstOccurrence([1, 3, 5, 7, 9], 4)).toBe(-1);
  });
});

// ── lowerBound ──────────────────────────────────────────────────────────────

describe('lowerBound', () => {
  const arr = [1, 3, 5, 7, 9];

  test('target present – returns its index', () => {
    expect(lowerBound(arr, 5)).toBe(2);
  });

  test('target between two elements – returns insertion point', () => {
    expect(lowerBound(arr, 4)).toBe(2); // 3 < 4 < 5, so first element >= 4 is 5 at index 2
  });

  test('target smaller than all – returns 0', () => {
    expect(lowerBound(arr, 0)).toBe(0);
  });

  test('target larger than all – returns arr.length', () => {
    expect(lowerBound(arr, 10)).toBe(5);
  });

  test('empty array returns 0', () => {
    expect(lowerBound([], 5)).toBe(0);
  });

  test('duplicates – returns first matching index', () => {
    expect(lowerBound([2, 2, 2, 5], 2)).toBe(0);
  });
});

// ── maxSubarrayDivideConquer ─────────────────────────────────────────────────

describe('maxSubarrayDivideConquer', () => {
  test('all positive', () => {
    expect(maxSubarrayDivideConquer([1, 2, 3, 4, 5])).toBe(15);
  });

  test('all negative – returns the least negative', () => {
    expect(maxSubarrayDivideConquer([-3, -1, -4, -2])).toBe(-1);
  });

  test('mixed positive and negative', () => {
    expect(maxSubarrayDivideConquer([-2, 1, -3, 4, -1, 2, 1, -5, 4])).toBe(6);
  });

  test('single element positive', () => {
    expect(maxSubarrayDivideConquer([5])).toBe(5);
  });

  test('single element negative', () => {
    expect(maxSubarrayDivideConquer([-5])).toBe(-5);
  });

  test('empty array returns 0', () => {
    expect(maxSubarrayDivideConquer([])).toBe(0);
  });

  test('crossing the midpoint gives best answer', () => {
    // Best subarray spans across the midpoint
    expect(maxSubarrayDivideConquer([1, -1, 10, -1, 1])).toBe(10);
  });
});

// ── kWayMerge ───────────────────────────────────────────────────────────────

describe('kWayMerge', () => {
  test('merges two sorted arrays', () => {
    expect(kWayMerge([[1, 3, 5], [2, 4, 6]])).toEqual([1, 2, 3, 4, 5, 6]);
  });

  test('merges three sorted arrays', () => {
    expect(kWayMerge([[1, 4], [2, 5], [3, 6]])).toEqual([1, 2, 3, 4, 5, 6]);
  });

  test('handles empty sub-arrays', () => {
    expect(kWayMerge([[], [1, 2], []])).toEqual([1, 2]);
  });

  test('single array passthrough', () => {
    expect(kWayMerge([[5, 3, 1]])).toEqual([1, 3, 5]);
  });

  test('no arrays', () => {
    expect(kWayMerge([])).toEqual([]);
  });
});
