'use strict';

const {
  lcs,
  lcsMemo,
  knapsack01,
  knapsack01Memo,
  matrixChainMultiplication,
  kadane,
} = require('../src/dynamicProgramming');

// ── LCS (tabulation) ────────────────────────────────────────────────────────

describe('lcs – tabulation', () => {
  test('classic example: AGGTAB & GXTXAYB = length 4', () => {
    const result = lcs('AGGTAB', 'GXTXAYB');
    expect(result.length).toBe(4);
  });

  test('identical strings – LCS is the string itself', () => {
    const result = lcs('ABCDE', 'ABCDE');
    expect(result.length).toBe(5);
    expect(result.lcs).toBe('ABCDE');
  });

  test('no common characters – LCS length is 0', () => {
    const result = lcs('ABC', 'DEF');
    expect(result.length).toBe(0);
    expect(result.lcs).toBe('');
  });

  test('one empty string – LCS is empty', () => {
    expect(lcs('', 'ABC').length).toBe(0);
    expect(lcs('ABC', '').length).toBe(0);
  });

  test('both empty – LCS is empty', () => {
    expect(lcs('', '').length).toBe(0);
  });

  test('single matching character', () => {
    expect(lcs('A', 'A').length).toBe(1);
  });

  test('ABCBDAB & BDCAB = length 4', () => {
    expect(lcs('ABCBDAB', 'BDCAB').length).toBe(4);
  });

  test('LCS reconstruction exercises the dp[i-1][j] > dp[i][j-1] (go-up) path', () => {
    // For "ABCBDAB" / "BDCAB", backtracking visits a cell where going up is optimal
    const { length, lcs: seq } = lcs('ABCBDAB', 'BDCAB');
    expect(length).toBe(4);
    // seq must be a valid subsequence of both inputs
    function isSub(sub, s) {
      let i = 0;
      for (const c of s) { if (c === sub[i]) i++; }
      return i === sub.length;
    }
    expect(isSub(seq, 'ABCBDAB')).toBe(true);
    expect(isSub(seq, 'BDCAB')).toBe(true);
  });

  test('LCS reconstruction is a valid subsequence of both inputs', () => {
    const str1 = 'AGGTAB', str2 = 'GXTXAYB';
    const { lcs: subsequence } = lcs(str1, str2);

    function isSubsequence(sub, s) {
      let i = 0;
      for (const ch of s) {
        if (ch === sub[i]) i++;
        if (i === sub.length) return true;
      }
      return i === sub.length;
    }

    expect(isSubsequence(subsequence, str1)).toBe(true);
    expect(isSubsequence(subsequence, str2)).toBe(true);
  });
});

// ── LCS (memoisation) ───────────────────────────────────────────────────────

describe('lcsMemo', () => {
  test('matches tabulation length for AGGTAB & GXTXAYB', () => {
    expect(lcsMemo('AGGTAB', 'GXTXAYB')).toBe(lcs('AGGTAB', 'GXTXAYB').length);
  });

  test('empty strings', () => {
    expect(lcsMemo('', 'ABC')).toBe(0);
    expect(lcsMemo('ABC', '')).toBe(0);
  });

  test('identical strings', () => {
    expect(lcsMemo('HELLO', 'HELLO')).toBe(5);
  });
});

// ── 0/1 Knapsack (tabulation) ────────────────────────────────────────────────

describe('knapsack01 – tabulation', () => {
  const classic = [
    { w: 1, v: 1 },
    { w: 3, v: 4 },
    { w: 4, v: 5 },
    { w: 5, v: 7 },
  ];

  test('classic bag, capacity 7 → maxValue 9', () => {
    expect(knapsack01(classic, 7).maxValue).toBe(9);
  });

  test('zero capacity → maxValue 0', () => {
    expect(knapsack01(classic, 0).maxValue).toBe(0);
  });

  test('empty items → maxValue 0', () => {
    expect(knapsack01([], 10).maxValue).toBe(0);
  });

  test('all items fit', () => {
    const items = [{ w: 1, v: 3 }, { w: 2, v: 5 }];
    const result = knapsack01(items, 10);
    expect(result.maxValue).toBe(8);
  });

  test('no item fits (all too heavy)', () => {
    const items = [{ w: 10, v: 100 }];
    expect(knapsack01(items, 5).maxValue).toBe(0);
  });

  test('single item that exactly fits', () => {
    expect(knapsack01([{ w: 5, v: 50 }], 5).maxValue).toBe(50);
  });

  test('picked array is a valid subset', () => {
    const { picked } = knapsack01(classic, 7);
    picked.forEach(idx => {
      expect(idx).toBeGreaterThanOrEqual(0);
      expect(idx).toBeLessThan(classic.length);
    });
  });

  test('picked items weight does not exceed capacity', () => {
    const { picked } = knapsack01(classic, 7);
    const totalWeight = picked.reduce((acc, i) => acc + classic[i].w, 0);
    expect(totalWeight).toBeLessThanOrEqual(7);
  });

  test('greedy is NOT always optimal – tabulation beats it', () => {
    // Items where greedy by value/weight fails
    const items = [{ w: 10, v: 60 }, { w: 20, v: 100 }, { w: 30, v: 120 }];
    const result = knapsack01(items, 50);
    expect(result.maxValue).toBe(220);
  });
});

// ── 0/1 Knapsack (memoisation) ───────────────────────────────────────────────

describe('knapsack01Memo', () => {
  test('matches tabulation for classic bag', () => {
    const items = [{ w: 1, v: 1 }, { w: 3, v: 4 }, { w: 4, v: 5 }, { w: 5, v: 7 }];
    expect(knapsack01Memo(items, 7)).toBe(knapsack01(items, 7).maxValue);
  });

  test('empty items', () => {
    expect(knapsack01Memo([], 10)).toBe(0);
  });

  test('zero capacity', () => {
    expect(knapsack01Memo([{ w: 1, v: 1 }], 0)).toBe(0);
  });
});

// ── Matrix Chain Multiplication ──────────────────────────────────────────────

describe('matrixChainMultiplication', () => {
  test('two matrices: p×q and q×r = p*q*r multiplications', () => {
    // [10, 30, 5] → one chain: 10×30 × 30×5 = 10*30*5 = 1500
    expect(matrixChainMultiplication([10, 30, 5])).toBe(1500);
  });

  test('three matrices ABCD classic textbook example', () => {
    // dims [1,2,3,4] → min cost = 1*2*4 + 1*3*4 = 8+12=20 ? Let's verify:
    // A:1x2, B:2x3, C:3x4
    // (AB)C: 1*2*3 + 1*3*4 = 6 + 12 = 18
    // A(BC): 2*3*4 + 1*2*4 = 24 + 8 = 32
    expect(matrixChainMultiplication([1, 2, 3, 4])).toBe(18);
  });

  test('standard textbook: [10,30,5,60] = 4500', () => {
    // min order: (A(BC)) = 30*5*60 + 10*30*60 = 9000+18000=27000? Let me recalculate
    // (AB)C: 10*30*5 + 10*5*60 = 1500 + 3000 = 4500
    // A(BC): 30*5*60 + 10*30*60 = 9000 + 18000 = 27000
    expect(matrixChainMultiplication([10, 30, 5, 60])).toBe(4500);
  });

  test('single matrix – no multiplication needed', () => {
    expect(matrixChainMultiplication([5, 10])).toBe(0);
  });

  test('empty or one dimension – returns 0', () => {
    expect(matrixChainMultiplication([])).toBe(0);
    expect(matrixChainMultiplication([5])).toBe(0);
  });
});

// ── Kadane's Algorithm ───────────────────────────────────────────────────────

describe('kadane', () => {
  test('standard mixed array: max subarray sum = 6', () => {
    const result = kadane([-2, 1, -3, 4, -1, 2, 1, -5, 4]);
    expect(result.maxSum).toBe(6);
  });

  test('all positive – entire array is the best subarray', () => {
    const result = kadane([1, 2, 3, 4, 5]);
    expect(result.maxSum).toBe(15);
    expect(result.start).toBe(0);
    expect(result.end).toBe(4);
  });

  test('all negative – returns the maximum (least negative) element', () => {
    const result = kadane([-3, -1, -4, -2]);
    expect(result.maxSum).toBe(-1);
    expect(result.start).toBe(1);
    expect(result.end).toBe(1);
  });

  test('single element', () => {
    const result = kadane([7]);
    expect(result.maxSum).toBe(7);
    expect(result.start).toBe(0);
    expect(result.end).toBe(0);
  });

  test('empty array', () => {
    const result = kadane([]);
    expect(result.maxSum).toBe(0);
  });

  test('subarray boundaries are correct', () => {
    // [−2, 1, −3, 4, −1, 2, 1, −5, 4] → best [4,−1,2,1] at indices 3–6
    const result = kadane([-2, 1, -3, 4, -1, 2, 1, -5, 4]);
    expect(result.start).toBe(3);
    expect(result.end).toBe(6);
  });

  test('best subarray at the very start', () => {
    const result = kadane([5, 4, 3, -20, 1]);
    expect(result.maxSum).toBe(12);
    expect(result.start).toBe(0);
  });

  test('best subarray at the very end', () => {
    const result = kadane([-5, -1, 3, 4, 5]);
    expect(result.maxSum).toBe(12);
    expect(result.end).toBe(4);
  });

  test('two elements', () => {
    expect(kadane([-1, 2]).maxSum).toBe(2);
    expect(kadane([3, -1]).maxSum).toBe(3);
  });
});
