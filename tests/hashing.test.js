'use strict';

const {
  hashMod,
  ChainingHashTable,
  LinearProbingHashTable,
  QuadraticProbingHashTable,
  DoubleHashingHashTable,
} = require('../src/hashing');

// ── hashMod ──────────────────────────────────────────────────────────────────

describe('hashMod', () => {
  test('18 % 7 === 4 (matches visualiser slide)', () => {
    expect(hashMod(18, 7)).toBe(4);
  });

  test('25 % 7 === 4', () => {
    expect(hashMod(25, 7)).toBe(4);
  });

  test('zero key hashes to 0', () => {
    expect(hashMod(0, 7)).toBe(0);
  });

  test('result is always in range [0, size)', () => {
    for (let key = 0; key < 50; key++) {
      const h = hashMod(key, 7);
      expect(h).toBeGreaterThanOrEqual(0);
      expect(h).toBeLessThan(7);
    }
  });
});

// ── Shared contract tests ─────────────────────────────────────────────────────

function sharedHashTableTests(TableClass, size = 7) {
  test('inserted key is found', () => {
    const ht = new TableClass(size);
    ht.insert(18);
    expect(ht.search(18)).toBeTruthy();
    if (typeof ht.search(18) === 'number') expect(ht.search(18)).toBeGreaterThanOrEqual(0);
  });

  test('missing key is not found', () => {
    const ht = new TableClass(size);
    const result = ht.search(99);
    if (typeof result === 'boolean') expect(result).toBe(false);
    else expect(result).toBe(-1);
  });

  test('deleted key is no longer found', () => {
    const ht = new TableClass(size);
    ht.insert(18);
    ht.delete(18);
    const result = ht.search(18);
    if (typeof result === 'boolean') expect(result).toBe(false);
    else expect(result).toBe(-1);
  });

  test('delete returns false for a missing key', () => {
    const ht = new TableClass(size);
    expect(ht.delete(99)).toBe(false);
  });

  test('can insert multiple keys', () => {
    const ht = new TableClass(size);
    [18, 25, 32].forEach(k => ht.insert(k));
    [18, 25, 32].forEach(k => {
      const result = ht.search(k);
      if (typeof result === 'boolean') expect(result).toBe(true);
      else expect(result).toBeGreaterThanOrEqual(0);
    });
  });

  test('keys that collide in the same slot are all retrievable', () => {
    const ht = new TableClass(size);
    // 4, 11, 18 all hash to slot 4 with size=7
    [4, 11, 18].forEach(k => ht.insert(k));
    [4, 11, 18].forEach(k => {
      const result = ht.search(k);
      if (typeof result === 'boolean') expect(result).toBe(true);
      else expect(result).toBeGreaterThanOrEqual(0);
    });
  });
}

// ── ChainingHashTable ─────────────────────────────────────────────────────────

describe('ChainingHashTable', () => {
  sharedHashTableTests(ChainingHashTable);

  test('collision keys land in the same chain', () => {
    const ht = new ChainingHashTable(7);
    ht.insert(18); // slot 4
    ht.insert(25); // slot 4
    expect(ht.getChain(4)).toContain(18);
    expect(ht.getChain(4)).toContain(25);
  });

  test('inserting the same key twice does not duplicate it', () => {
    const ht = new ChainingHashTable(7);
    ht.insert(18);
    ht.insert(18);
    expect(ht.getChain(4).length).toBe(1);
  });

  test('delete removes from chain', () => {
    const ht = new ChainingHashTable(7);
    ht.insert(18);
    ht.insert(25);
    ht.delete(18);
    expect(ht.getChain(4)).not.toContain(18);
    expect(ht.getChain(4)).toContain(25);
  });

  test('search returns true/false', () => {
    const ht = new ChainingHashTable(7);
    ht.insert(10);
    expect(ht.search(10)).toBe(true);
    expect(ht.search(99)).toBe(false);
  });
});

// ── LinearProbingHashTable ───────────────────────────────────────────────────

describe('LinearProbingHashTable', () => {
  sharedHashTableTests(LinearProbingHashTable);

  test('visualiser example: 18→slot 4, 25→slot 5, 32→slot 6, 39→slot 0', () => {
    const ht = new LinearProbingHashTable(7);
    [18, 25, 32, 39].forEach(k => ht.insert(k));
    expect(ht.search(18)).toBe(4);
    expect(ht.search(25)).toBe(5);
    expect(ht.search(32)).toBe(6);
    expect(ht.search(39)).toBe(0);
  });

  test('probing wraps around the table', () => {
    const ht = new LinearProbingHashTable(7);
    // slot 6 first, then wraps
    [6, 13, 20].forEach(k => ht.insert(k)); // all hash to 6 mod 7
    expect(ht.search(6)).toBeGreaterThanOrEqual(0);
    expect(ht.search(13)).toBeGreaterThanOrEqual(0);
    expect(ht.search(20)).toBeGreaterThanOrEqual(0);
  });

  test('getSlot returns null for empty slot', () => {
    const ht = new LinearProbingHashTable(7);
    expect(ht.getSlot(0)).toBeNull();
  });

  test('getSlot returns "DELETED" after deletion', () => {
    const ht = new LinearProbingHashTable(7);
    ht.insert(7); // slot 0
    ht.delete(7);
    expect(ht.getSlot(0)).toBe('DELETED');
  });

  test('search past a DELETED slot still finds key', () => {
    const ht = new LinearProbingHashTable(7);
    ht.insert(18); // slot 4
    ht.insert(25); // slot 5 (collision)
    ht.delete(18); // slot 4 marked DELETED
    expect(ht.search(25)).toBe(5);
  });

  test('returns false when table is full and insert is attempted', () => {
    const ht = new LinearProbingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    expect(ht.insert(100)).toBe(false);
  });

  test('search returns -1 when table is full and key absent', () => {
    const ht = new LinearProbingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    expect(ht.search(50)).toBe(-1);
  });

  test('delete returns false when table is full and key absent', () => {
    const ht = new LinearProbingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    expect(ht.delete(50)).toBe(false);
  });
});

// ── QuadraticProbingHashTable ────────────────────────────────────────────────

describe('QuadraticProbingHashTable', () => {
  sharedHashTableTests(QuadraticProbingHashTable);

  test('probe sequence uses i² offsets', () => {
    const ht = new QuadraticProbingHashTable(11);
    // key=10 → h=10; probe: 10, (10+1)%11=0, (10+4)%11=3
    ht.insert(10);
    ht.insert(21); // same h=10, goes to slot 0
    expect(ht.search(10)).toBe(10);
    expect(ht.search(21)).toBe(0);
  });

  test('returns false when table is full', () => {
    const ht = new QuadraticProbingHashTable(7);
    // Fill table with 7 distinct non-colliding keys
    for (let k = 0; k < 7; k++) ht.insert(k);
    // Inserting an 8th key should return false
    expect(ht.insert(100)).toBe(false);
  });

  test('search exhausts all probes and returns -1 on full table miss', () => {
    const ht = new QuadraticProbingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    // 50 is not in the table; all slots are occupied
    expect(ht.search(50)).toBe(-1);
  });

  test('delete exhausts all probes and returns false on full table miss', () => {
    const ht = new QuadraticProbingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    expect(ht.delete(50)).toBe(false);
  });
});

// ── DoubleHashingHashTable ───────────────────────────────────────────────────

describe('DoubleHashingHashTable', () => {
  sharedHashTableTests(DoubleHashingHashTable);

  test('h2 is never 0 (avoids infinite loop)', () => {
    const ht = new DoubleHashingHashTable(7);
    // For any key, h2 = 1 + (key % 6) is in range [1, 6]
    for (let k = 0; k < 20; k++) {
      expect(ht._h2(k)).toBeGreaterThanOrEqual(1);
    }
  });

  test('keys with same h1 use different step sizes', () => {
    const ht = new DoubleHashingHashTable(7);
    // Both 0 and 7 have h1=0; h2(0)=1, h2(7)=1+7%6=2
    ht.insert(0);
    ht.insert(7);
    expect(ht.search(0)).toBeGreaterThanOrEqual(0);
    expect(ht.search(7)).toBeGreaterThanOrEqual(0);
    expect(ht.search(0)).not.toBe(ht.search(7));
  });

  test('returns false when table is full', () => {
    const ht = new DoubleHashingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    expect(ht.insert(100)).toBe(false);
  });

  test('search returns -1 when table is full and key absent', () => {
    const ht = new DoubleHashingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    expect(ht.search(50)).toBe(-1);
  });

  test('delete returns false when table is full and key absent', () => {
    const ht = new DoubleHashingHashTable(7);
    for (let k = 0; k < 7; k++) ht.insert(k);
    expect(ht.delete(50)).toBe(false);
  });
});
