'use strict';

/**
 * Hash-table implementations extracted from the interactive visualisers.
 * All implementations use integer keys and fixed-size tables.
 */

/**
 * Simple modular hash function: key % tableSize.
 * Matches hashing_functions_in_hashing.html.
 */
function hashMod(key, tableSize) {
  return ((key % tableSize) + tableSize) % tableSize;
}

// ---------------------------------------------------------------------------
// Separate Chaining
// ---------------------------------------------------------------------------

/**
 * Hash table with separate chaining.
 * Matches chaining_in_hashing.html.
 */
class ChainingHashTable {
  constructor(size = 7) {
    this.size = size;
    this.table = Array.from({ length: size }, () => []);
  }

  _hash(key) {
    return hashMod(key, this.size);
  }

  insert(key) {
    const idx = this._hash(key);
    if (!this.table[idx].includes(key)) this.table[idx].push(key);
  }

  search(key) {
    const idx = this._hash(key);
    return this.table[idx].includes(key);
  }

  delete(key) {
    const idx = this._hash(key);
    const chain = this.table[idx];
    const pos = chain.indexOf(key);
    if (pos !== -1) { chain.splice(pos, 1); return true; }
    return false;
  }

  /** Returns the chain at a given slot (for inspection in tests). */
  getChain(slot) {
    return [...this.table[slot]];
  }
}

// ---------------------------------------------------------------------------
// Linear Probing
// ---------------------------------------------------------------------------

const DELETED = Symbol('DELETED');

/**
 * Hash table with linear probing.
 * Matches linear_probing_in_hashing.html.
 */
class LinearProbingHashTable {
  constructor(size = 7) {
    this.size = size;
    this.table = Array(size).fill(null);
  }

  _hash(key) {
    return hashMod(key, this.size);
  }

  insert(key) {
    let idx = this._hash(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (idx + i) % this.size;
      if (this.table[slot] === null || this.table[slot] === DELETED) {
        this.table[slot] = key;
        return true;
      }
      if (this.table[slot] === key) return false; // already present
    }
    return false; // table full
  }

  search(key) {
    let idx = this._hash(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (idx + i) % this.size;
      if (this.table[slot] === null) return -1;
      if (this.table[slot] === key) return slot;
    }
    return -1;
  }

  delete(key) {
    let idx = this._hash(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (idx + i) % this.size;
      if (this.table[slot] === null) return false;
      if (this.table[slot] === key) {
        this.table[slot] = DELETED;
        return true;
      }
    }
    return false;
  }

  getSlot(slot) {
    const v = this.table[slot];
    return v === null ? null : v === DELETED ? 'DELETED' : v;
  }
}

// ---------------------------------------------------------------------------
// Quadratic Probing
// ---------------------------------------------------------------------------

/**
 * Hash table with quadratic probing: h(k, i) = (h(k) + i²) % size.
 * Matches quadratic_probing_in_hashing.html.
 */
class QuadraticProbingHashTable {
  constructor(size = 7) {
    this.size = size;
    this.table = Array(size).fill(null);
  }

  _hash(key) {
    return hashMod(key, this.size);
  }

  insert(key) {
    const h = this._hash(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (h + i * i) % this.size;
      if (this.table[slot] === null || this.table[slot] === DELETED) {
        this.table[slot] = key;
        return true;
      }
      if (this.table[slot] === key) return false;
    }
    return false;
  }

  search(key) {
    const h = this._hash(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (h + i * i) % this.size;
      if (this.table[slot] === null) return -1;
      if (this.table[slot] === key) return slot;
    }
    return -1;
  }

  delete(key) {
    const h = this._hash(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (h + i * i) % this.size;
      if (this.table[slot] === null) return false;
      if (this.table[slot] === key) {
        this.table[slot] = DELETED;
        return true;
      }
    }
    return false;
  }
}

// ---------------------------------------------------------------------------
// Double Hashing
// ---------------------------------------------------------------------------

/**
 * Hash table with double hashing.
 * h1(k) = k % size
 * h2(k) = 1 + (k % (size - 1))
 * Matches double_hashing_in_hashing.html.
 */
class DoubleHashingHashTable {
  constructor(size = 7) {
    this.size = size;
    this.table = Array(size).fill(null);
  }

  _h1(key) {
    return hashMod(key, this.size);
  }

  _h2(key) {
    return 1 + (key % (this.size - 1));
  }

  insert(key) {
    const h1 = this._h1(key);
    const h2 = this._h2(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (h1 + i * h2) % this.size;
      if (this.table[slot] === null || this.table[slot] === DELETED) {
        this.table[slot] = key;
        return true;
      }
      if (this.table[slot] === key) return false;
    }
    return false;
  }

  search(key) {
    const h1 = this._h1(key);
    const h2 = this._h2(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (h1 + i * h2) % this.size;
      if (this.table[slot] === null) return -1;
      if (this.table[slot] === key) return slot;
    }
    return -1;
  }

  delete(key) {
    const h1 = this._h1(key);
    const h2 = this._h2(key);
    for (let i = 0; i < this.size; i++) {
      const slot = (h1 + i * h2) % this.size;
      if (this.table[slot] === null) return false;
      if (this.table[slot] === key) {
        this.table[slot] = DELETED;
        return true;
      }
    }
    return false;
  }
}

module.exports = {
  hashMod,
  ChainingHashTable,
  LinearProbingHashTable,
  QuadraticProbingHashTable,
  DoubleHashingHashTable,
};
