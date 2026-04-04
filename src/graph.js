'use strict';

/**
 * Graph algorithms extracted from the interactive visualisers.
 * Graphs are represented as adjacency lists: Map<node, node[]>.
 */

/**
 * Build an adjacency list from an edge list.
 * directed = false produces an undirected graph.
 */
function buildAdjList(nodes, edges, directed = false) {
  const adj = new Map(nodes.map(n => [n, []]));
  edges.forEach(([u, v]) => {
    adj.get(u).push(v);
    if (!directed) adj.get(v).push(u);
  });
  return adj;
}

/**
 * BFS – returns nodes in discovery order starting from `start`.
 * Matches bfs_traversal.html (classroom and bridge samples).
 */
function bfs(adj, start) {
  const visited = new Set([start]);
  const order = [];
  const queue = [start];

  while (queue.length) {
    const node = queue.shift();
    order.push(node);
    for (const neighbour of (adj.get(node) || [])) {
      if (!visited.has(neighbour)) {
        visited.add(neighbour);
        queue.push(neighbour);
      }
    }
  }

  return order;
}

/**
 * DFS (iterative) – returns nodes in discovery order starting from `start`.
 * Matches dfs_traversal.html.
 */
function dfs(adj, start) {
  const visited = new Set();
  const order = [];
  const stack = [start];

  while (stack.length) {
    const node = stack.pop();
    if (visited.has(node)) continue;
    visited.add(node);
    order.push(node);
    const neighbours = [...(adj.get(node) || [])].reverse();
    for (const neighbour of neighbours) {
      if (!visited.has(neighbour)) stack.push(neighbour);
    }
  }

  return order;
}

/**
 * DFS (recursive) – returns nodes in discovery order.
 */
function dfsRecursive(adj, start) {
  const visited = new Set();
  const order = [];

  function visit(node) {
    visited.add(node);
    order.push(node);
    for (const neighbour of (adj.get(node) || [])) {
      if (!visited.has(neighbour)) visit(neighbour);
    }
  }

  visit(start);
  return order;
}

/**
 * Dijkstra's shortest-path algorithm.
 * edges: Array of { from, to, weight } (undirected – each edge is treated both ways)
 * Returns { distances, previous } maps.
 * Matches dijkstra_algorithm.html.
 */
function dijkstra(nodes, edges, source) {
  const INF = Infinity;
  const distances = {};
  const previous = {};
  const unvisited = new Set(nodes);

  nodes.forEach(n => {
    distances[n] = n === source ? 0 : INF;
    previous[n] = null;
  });

  while (unvisited.size > 0) {
    // Pick unvisited node with minimum distance
    let current = null;
    for (const n of unvisited) {
      if (current === null || distances[n] < distances[current]) current = n;
    }
    if (current === null || distances[current] === INF) break;

    unvisited.delete(current);

    for (const edge of edges) {
      let neighbour = null;
      if (edge.from === current && unvisited.has(edge.to)) neighbour = edge.to;
      else if (edge.to === current && unvisited.has(edge.from)) neighbour = edge.from;
      if (neighbour === null) continue;

      const newDist = distances[current] + edge.weight;
      if (newDist < distances[neighbour]) {
        distances[neighbour] = newDist;
        previous[neighbour] = current;
      }
    }
  }

  return { distances, previous };
}

/**
 * Reconstruct the shortest path from source to target using the `previous` map
 * returned by dijkstra(). Returns an empty array if no path exists.
 */
function reconstructPath(previous, source, target) {
  const path = [];
  let current = target;
  while (current !== null) {
    path.unshift(current);
    if (current === source) return path;
    current = previous[current];
  }
  return [];
}

/**
 * Disjoint Set Union (Union by size + path compression).
 * Matches disjoint_set_union.html.
 */
class DSU {
  constructor(elements) {
    this.parent = {};
    this.size = {};
    elements.forEach(e => {
      this.parent[e] = e;
      this.size[e] = 1;
    });
  }

  find(x) {
    if (this.parent[x] !== x) {
      this.parent[x] = this.find(this.parent[x]);
    }
    return this.parent[x];
  }

  union(a, b) {
    const rootA = this.find(a);
    const rootB = this.find(b);
    if (rootA === rootB) return false;
    if (this.size[rootA] < this.size[rootB]) {
      this.parent[rootA] = rootB;
      this.size[rootB] += this.size[rootA];
    } else {
      this.parent[rootB] = rootA;
      this.size[rootA] += this.size[rootB];
    }
    return true;
  }

  connected(a, b) {
    return this.find(a) === this.find(b);
  }

  /** Number of distinct sets. */
  countSets() {
    return new Set(Object.keys(this.parent).map(k => this.find(k))).size;
  }
}

module.exports = {
  buildAdjList,
  bfs,
  dfs,
  dfsRecursive,
  dijkstra,
  reconstructPath,
  DSU,
};
