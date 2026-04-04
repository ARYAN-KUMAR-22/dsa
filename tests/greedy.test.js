'use strict';

const {
  fractionalKnapsack,
  optimalMergePattern,
} = require('../src/greedy');

// ── fractionalKnapsack ───────────────────────────────────────────────────────

describe('fractionalKnapsack', () => {
  test('classic example: items sorted by ratio, exact total', () => {
    // From knapsack_problem_fractional.html: capacity=50
    // Item 1: v=60, w=10 → ratio=6 → take all
    // Item 2: v=100, w=20 → ratio=5 → take all
    // Item 3: v=120, w=30 → ratio=4 → take 20/30
    const items = [
      { value: 60, weight: 10 },
      { value: 100, weight: 20 },
      { value: 120, weight: 30 },
    ];
    const { maxValue } = fractionalKnapsack(items, 50);
    expect(maxValue).toBeCloseTo(240, 5);
  });

  test('capacity large enough to take everything', () => {
    const items = [{ value: 10, weight: 5 }, { value: 20, weight: 10 }];
    const { maxValue, fractions } = fractionalKnapsack(items, 100);
    expect(maxValue).toBeCloseTo(30, 5);
    fractions.forEach(f => expect(f).toBeCloseTo(1, 5));
  });

  test('capacity of zero → maxValue is 0', () => {
    const items = [{ value: 10, weight: 5 }];
    expect(fractionalKnapsack(items, 0).maxValue).toBe(0);
  });

  test('no items → maxValue is 0', () => {
    expect(fractionalKnapsack([], 50).maxValue).toBe(0);
  });

  test('single item – capacity exactly equals weight', () => {
    const { maxValue, fractions } = fractionalKnapsack([{ value: 30, weight: 10 }], 10);
    expect(maxValue).toBeCloseTo(30, 5);
    expect(fractions[0]).toBeCloseTo(1, 5);
  });

  test('single item – capacity less than weight (take a fraction)', () => {
    const { maxValue, fractions } = fractionalKnapsack([{ value: 30, weight: 10 }], 5);
    expect(maxValue).toBeCloseTo(15, 5);
    expect(fractions[0]).toBeCloseTo(0.5, 5);
  });

  test('greedy picks highest ratio first', () => {
    // item B has the highest ratio; it should be taken first
    const items = [
      { value: 10, weight: 10 }, // ratio 1
      { value: 30, weight: 10 }, // ratio 3 – best
      { value: 20, weight: 10 }, // ratio 2
    ];
    const { fractions } = fractionalKnapsack(items, 10);
    expect(fractions[1]).toBeCloseTo(1, 5); // item B taken in full
    expect(fractions[0]).toBeCloseTo(0, 5);
    expect(fractions[2]).toBeCloseTo(0, 5);
  });

  test('fractions are between 0 and 1 inclusive', () => {
    const items = [{ value: 10, weight: 5 }, { value: 20, weight: 10 }, { value: 5, weight: 3 }];
    const { fractions } = fractionalKnapsack(items, 8);
    fractions.forEach(f => {
      expect(f).toBeGreaterThanOrEqual(0);
      expect(f).toBeLessThanOrEqual(1);
    });
  });

  test('total weight taken does not exceed capacity', () => {
    const items = [{ value: 60, weight: 10 }, { value: 100, weight: 20 }, { value: 120, weight: 30 }];
    const capacity = 50;
    const { fractions } = fractionalKnapsack(items, capacity);
    const totalWeight = fractions.reduce((sum, f, i) => sum + f * items[i].weight, 0);
    expect(totalWeight).toBeLessThanOrEqual(capacity + 1e-9);
  });
});

// ── optimalMergePattern ──────────────────────────────────────────────────────

describe('optimalMergePattern', () => {
  test('two files: cost is their sum', () => {
    expect(optimalMergePattern([2, 3])).toBe(5);
  });

  test('three files: classic example', () => {
    // files [2, 3, 4]: merge smallest first
    // merge(2,3)=5, cost=5; then merge(4,5)=9, cost=9; total=14
    expect(optimalMergePattern([2, 3, 4])).toBe(14);
  });

  test('four files: greedy gives minimum cost', () => {
    // files [1, 2, 3, 4]
    // merge 1+2=3, cost=3 → [3,3,4]
    // merge 3+3=6, cost=6 → [4,6]
    // merge 4+6=10, cost=10 → total=19
    expect(optimalMergePattern([1, 2, 3, 4])).toBe(19);
  });

  test('single file – no merge needed', () => {
    expect(optimalMergePattern([5])).toBe(0);
  });

  test('empty array', () => {
    expect(optimalMergePattern([])).toBe(0);
  });

  test('all files same size', () => {
    // files [4,4,4,4]
    // merge 4+4=8, cost=8; merge 4+4=8, cost=8 → [8,8]
    // merge 8+8=16, cost=16; total=32
    expect(optimalMergePattern([4, 4, 4, 4])).toBe(32);
  });

  test('large files still produce the correct minimum cost', () => {
    // files [6, 3, 2, 7, 4]
    // sort → [2,3,4,6,7]
    // merge 2+3=5, cost=5  → [4,5,6,7]
    // merge 4+5=9, cost=9  → [6,7,9]
    // merge 6+7=13, cost=13 → [9,13]
    // merge 9+13=22, cost=22 → total=49
    expect(optimalMergePattern([6, 3, 2, 7, 4])).toBe(49);
  });
});
