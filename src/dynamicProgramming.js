'use strict';

/**
 * Dynamic-programming algorithms extracted from the interactive visualisers.
 */

/**
 * Longest Common Subsequence (tabulation).
 * Returns { length, lcs } where lcs is one optimal subsequence string.
 * Matches lcs_tabulation.html and longest_common_subsequence.html.
 */
function lcs(str1, str2) {
  const m = str1.length, n = str2.length;
  const dp = Array.from({ length: m + 1 }, () => Array(n + 1).fill(0));

  for (let i = 1; i <= m; i++) {
    for (let j = 1; j <= n; j++) {
      if (str1[i - 1] === str2[j - 1]) {
        dp[i][j] = dp[i - 1][j - 1] + 1;
      } else {
        dp[i][j] = Math.max(dp[i - 1][j], dp[i][j - 1]);
      }
    }
  }

  // Backtrack to reconstruct one LCS
  let i = m, j = n, result = '';
  while (i > 0 && j > 0) {
    if (str1[i - 1] === str2[j - 1]) {
      result = str1[i - 1] + result;
      i--;
      j--;
    } else if (dp[i - 1][j] > dp[i][j - 1]) {
      i--;
    } else {
      j--;
    }
  }

  return { length: dp[m][n], lcs: result };
}

/**
 * 0/1 Knapsack (tabulation).
 * items: Array of { w: weight, v: value }
 * Returns { maxValue, picked } where picked is the array of selected item indices.
 * Matches zero_one_knapsack_tabulation.html.
 */
function knapsack01(items, capacity) {
  const n = items.length;
  const dp = Array.from({ length: n + 1 }, () => Array(capacity + 1).fill(0));

  for (let i = 1; i <= n; i++) {
    for (let w = 0; w <= capacity; w++) {
      dp[i][w] = dp[i - 1][w];
      if (items[i - 1].w <= w) {
        dp[i][w] = Math.max(dp[i][w], items[i - 1].v + dp[i - 1][w - items[i - 1].w]);
      }
    }
  }

  // Reconstruct selected items
  const picked = [];
  let w = capacity;
  for (let i = n; i > 0; i--) {
    if (dp[i][w] !== dp[i - 1][w]) {
      picked.push(i - 1);
      w -= items[i - 1].w;
    }
  }

  return { maxValue: dp[n][capacity], picked: picked.reverse() };
}

/**
 * Matrix Chain Multiplication (tabulation).
 * dims: Array of n+1 dimensions where matrix i has size dims[i] × dims[i+1].
 * Returns minimum scalar multiplications needed.
 * Matches matrix_chain_multiplication_tabulation.html.
 */
function matrixChainMultiplication(dims) {
  const n = dims.length - 1;
  if (n <= 1) return 0;

  const dp = Array.from({ length: n }, () => Array(n).fill(0));

  for (let len = 2; len <= n; len++) {
    for (let i = 0; i < n - len + 1; i++) {
      const j = i + len - 1;
      dp[i][j] = Infinity;
      for (let k = i; k < j; k++) {
        const cost = dp[i][k] + dp[k + 1][j] + dims[i] * dims[k + 1] * dims[j + 1];
        if (cost < dp[i][j]) dp[i][j] = cost;
      }
    }
  }

  return dp[0][n - 1];
}

/**
 * Kadane's Algorithm – maximum subarray sum in O(n).
 * Returns { maxSum, start, end } (inclusive indices of best subarray).
 * Matches max_subarray_dp.html and kadane_with_boundaries.html.
 */
function kadane(arr) {
  if (!arr.length) return { maxSum: 0, start: -1, end: -1 };

  let maxSum = arr[0], currentSum = arr[0];
  let start = 0, end = 0, tempStart = 0;

  for (let i = 1; i < arr.length; i++) {
    if (currentSum + arr[i] < arr[i]) {
      currentSum = arr[i];
      tempStart = i;
    } else {
      currentSum += arr[i];
    }
    if (currentSum > maxSum) {
      maxSum = currentSum;
      start = tempStart;
      end = i;
    }
  }

  return { maxSum, start, end };
}

/**
 * Longest Common Subsequence (memoised recursion).
 * Returns length only.
 * Matches lcs_memoization.html.
 */
function lcsMemo(str1, str2) {
  const memo = new Map();

  function solve(i, j) {
    if (i === 0 || j === 0) return 0;
    const key = `${i},${j}`;
    if (memo.has(key)) return memo.get(key);
    let result;
    if (str1[i - 1] === str2[j - 1]) {
      result = 1 + solve(i - 1, j - 1);
    } else {
      result = Math.max(solve(i - 1, j), solve(i, j - 1));
    }
    memo.set(key, result);
    return result;
  }

  return solve(str1.length, str2.length);
}

/**
 * 0/1 Knapsack (memoised recursion).
 * Returns max value only.
 * Matches zero_one_knapsack_memoization.html.
 */
function knapsack01Memo(items, capacity) {
  const memo = new Map();

  function solve(i, remaining) {
    if (i < 0 || remaining <= 0) return 0;
    const key = `${i},${remaining}`;
    if (memo.has(key)) return memo.get(key);
    let result = solve(i - 1, remaining);
    if (items[i].w <= remaining) {
      result = Math.max(result, items[i].v + solve(i - 1, remaining - items[i].w));
    }
    memo.set(key, result);
    return result;
  }

  return solve(items.length - 1, capacity);
}

module.exports = {
  lcs,
  lcsMemo,
  knapsack01,
  knapsack01Memo,
  matrixChainMultiplication,
  kadane,
};
