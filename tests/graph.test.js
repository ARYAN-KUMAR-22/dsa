'use strict';

const {
  buildAdjList,
  bfs,
  dfs,
  dfsRecursive,
  dijkstra,
  reconstructPath,
  DSU,
} = require('../src/graph');

// ── Shared graph fixtures ───────────────────────────────────────────────────

// Classroom graph from bfs_traversal.html
const classroomNodes = ['A', 'B', 'C', 'D', 'E', 'F'];
const classroomEdges = [
  ['A', 'B'], ['A', 'C'],
  ['B', 'D'], ['C', 'D'],
  ['C', 'E'], ['D', 'F'], ['E', 'F'],
];

// Weighted graph from dijkstra_algorithm.html
const dijkstraNodes = ['A', 'B', 'C', 'D', 'E'];
const dijkstraEdges = [
  { from: 'A', to: 'B', weight: 4 },
  { from: 'A', to: 'C', weight: 2 },
  { from: 'B', to: 'C', weight: 1 },
  { from: 'B', to: 'D', weight: 5 },
  { from: 'C', to: 'D', weight: 8 },
  { from: 'C', to: 'E', weight: 10 },
  { from: 'D', to: 'E', weight: 2 },
  { from: 'B', to: 'E', weight: 15 },
];

// ── buildAdjList ─────────────────────────────────────────────────────────────

describe('buildAdjList', () => {
  test('undirected: each edge appears in both adjacency lists', () => {
    const adj = buildAdjList(['A', 'B', 'C'], [['A', 'B'], ['B', 'C']]);
    expect(adj.get('A')).toContain('B');
    expect(adj.get('B')).toContain('A');
    expect(adj.get('B')).toContain('C');
    expect(adj.get('C')).toContain('B');
  });

  test('directed: edge only appears in one direction', () => {
    const adj = buildAdjList(['A', 'B'], [['A', 'B']], true);
    expect(adj.get('A')).toContain('B');
    expect(adj.get('B')).not.toContain('A');
  });

  test('isolated node has empty adjacency list', () => {
    const adj = buildAdjList(['A', 'B', 'C'], [['A', 'B']]);
    expect(adj.get('C')).toEqual([]);
  });
});

// ── BFS ──────────────────────────────────────────────────────────────────────

describe('bfs', () => {
  const adj = buildAdjList(classroomNodes, classroomEdges);

  test('visits all reachable nodes', () => {
    const order = bfs(adj, 'A');
    expect(order.sort()).toEqual([...classroomNodes].sort());
  });

  test('start node is visited first', () => {
    expect(bfs(adj, 'A')[0]).toBe('A');
  });

  test('level-order: direct neighbours before farther nodes', () => {
    const order = bfs(adj, 'A');
    const indexB = order.indexOf('B');
    const indexC = order.indexOf('C');
    const indexD = order.indexOf('D');
    // B and C are level-1, D is level-2 from A
    expect(indexB).toBeLessThan(indexD);
    expect(indexC).toBeLessThan(indexD);
  });

  test('each node appears exactly once', () => {
    const order = bfs(adj, 'A');
    expect(new Set(order).size).toBe(order.length);
  });

  test('disconnected graph: only reachable nodes are returned', () => {
    const adj2 = buildAdjList(['X', 'Y', 'Z'], [['X', 'Y']]);
    expect(bfs(adj2, 'X').sort()).toEqual(['X', 'Y']);
  });

  test('single node graph', () => {
    const adj3 = buildAdjList(['A'], []);
    expect(bfs(adj3, 'A')).toEqual(['A']);
  });
});

// ── DFS (iterative) ──────────────────────────────────────────────────────────

describe('dfs (iterative)', () => {
  const adj = buildAdjList(classroomNodes, classroomEdges);

  test('visits all reachable nodes', () => {
    expect(dfs(adj, 'A').sort()).toEqual([...classroomNodes].sort());
  });

  test('start node is visited first', () => {
    expect(dfs(adj, 'A')[0]).toBe('A');
  });

  test('each node appears exactly once', () => {
    const order = dfs(adj, 'A');
    expect(new Set(order).size).toBe(order.length);
  });

  test('disconnected: returns only reachable nodes', () => {
    const adj2 = buildAdjList(['P', 'Q', 'R'], [['P', 'Q']]);
    expect(dfs(adj2, 'P').sort()).toEqual(['P', 'Q']);
  });

  test('single node', () => {
    expect(dfs(buildAdjList(['X'], []), 'X')).toEqual(['X']);
  });
});

// ── DFS (recursive) ──────────────────────────────────────────────────────────

describe('dfsRecursive', () => {
  const adj = buildAdjList(classroomNodes, classroomEdges);

  test('visits all reachable nodes', () => {
    expect(dfsRecursive(adj, 'A').sort()).toEqual([...classroomNodes].sort());
  });

  test('start node is visited first', () => {
    expect(dfsRecursive(adj, 'A')[0]).toBe('A');
  });

  test('produces same set as iterative DFS', () => {
    expect(dfsRecursive(adj, 'A').sort()).toEqual(dfs(adj, 'A').sort());
  });
});

// ── Dijkstra's algorithm ─────────────────────────────────────────────────────

describe('dijkstra', () => {
  test('distance from A to itself is 0', () => {
    const { distances } = dijkstra(dijkstraNodes, dijkstraEdges, 'A');
    expect(distances['A']).toBe(0);
  });

  test('A → C: shortest path is 2 (direct edge)', () => {
    const { distances } = dijkstra(dijkstraNodes, dijkstraEdges, 'A');
    expect(distances['C']).toBe(2);
  });

  test('A → B: shortest path is 3 (A→C→B weight 2+1)', () => {
    const { distances } = dijkstra(dijkstraNodes, dijkstraEdges, 'A');
    expect(distances['B']).toBe(3);
  });

  test('A → D: shortest path is 8 (A→C→B→D weight 2+1+5)', () => {
    const { distances } = dijkstra(dijkstraNodes, dijkstraEdges, 'A');
    expect(distances['D']).toBe(8);
  });

  test('A → E: shortest path is 10 (A→C→B→D→E weight 2+1+5+2)', () => {
    const { distances } = dijkstra(dijkstraNodes, dijkstraEdges, 'A');
    expect(distances['E']).toBe(10);
  });

  test('unreachable node has Infinity distance', () => {
    const nodes = ['A', 'B', 'C'];
    const edges = [{ from: 'A', to: 'B', weight: 1 }]; // C is isolated
    const { distances } = dijkstra(nodes, edges, 'A');
    expect(distances['C']).toBe(Infinity);
  });

  test('single node graph', () => {
    const { distances } = dijkstra(['A'], [], 'A');
    expect(distances['A']).toBe(0);
  });
});

// ── reconstructPath ──────────────────────────────────────────────────────────

describe('reconstructPath', () => {
  test('reconstructs A → E path', () => {
    const { previous } = dijkstra(dijkstraNodes, dijkstraEdges, 'A');
    const path = reconstructPath(previous, 'A', 'E');
    expect(path[0]).toBe('A');
    expect(path[path.length - 1]).toBe('E');
  });

  test('path from source to itself is [source]', () => {
    const { previous } = dijkstra(dijkstraNodes, dijkstraEdges, 'A');
    expect(reconstructPath(previous, 'A', 'A')).toEqual(['A']);
  });

  test('no path to unreachable node returns []', () => {
    const nodes = ['A', 'B'];
    const { previous } = dijkstra(nodes, [], 'A');
    expect(reconstructPath(previous, 'A', 'B')).toEqual([]);
  });
});

// ── DSU ──────────────────────────────────────────────────────────────────────

describe('DSU', () => {
  test('initially every element is its own set', () => {
    const dsu = new DSU(['A', 'B', 'C']);
    expect(dsu.countSets()).toBe(3);
  });

  test('find returns the element itself when no union done', () => {
    const dsu = new DSU(['A', 'B']);
    expect(dsu.find('A')).toBe('A');
  });

  test('union merges two sets', () => {
    const dsu = new DSU(['A', 'B', 'C']);
    dsu.union('A', 'B');
    expect(dsu.countSets()).toBe(2);
    expect(dsu.connected('A', 'B')).toBe(true);
  });

  test('unconnected elements are not in the same set', () => {
    const dsu = new DSU(['A', 'B', 'C']);
    dsu.union('A', 'B');
    expect(dsu.connected('A', 'C')).toBe(false);
  });

  test('transitive union: A-B and B-C makes A and C connected', () => {
    const dsu = new DSU(['A', 'B', 'C']);
    dsu.union('A', 'B');
    dsu.union('B', 'C');
    expect(dsu.connected('A', 'C')).toBe(true);
    expect(dsu.countSets()).toBe(1);
  });

  test('union of already-connected elements returns false', () => {
    const dsu = new DSU(['A', 'B']);
    dsu.union('A', 'B');
    expect(dsu.union('A', 'B')).toBe(false);
  });

  test('campus sample from visualiser', () => {
    const dsu = new DSU(['A', 'B', 'C', 'D', 'E', 'F']);
    dsu.union('A', 'B');
    dsu.union('C', 'D');
    dsu.union('B', 'C');
    dsu.union('E', 'F');
    dsu.union('D', 'E');
    expect(dsu.countSets()).toBe(1);
    expect(dsu.connected('A', 'F')).toBe(true);
  });

  test('path compression: repeated find calls are consistent', () => {
    const dsu = new DSU(['A', 'B', 'C', 'D']);
    dsu.union('A', 'B');
    dsu.union('C', 'D');
    dsu.union('A', 'C');
    const root1 = dsu.find('A');
    const root2 = dsu.find('D');
    expect(root1).toBe(root2);
  });

  test('union by size: smaller root attaches under larger root', () => {
    // Build a larger set first under C, then merge a singleton A into it
    const dsu = new DSU(['A', 'B', 'C', 'D']);
    dsu.union('C', 'D'); // C root has size 2
    dsu.union('A', 'C'); // rootA=A(size 1) < rootC(size 2) → A attaches under C
    expect(dsu.connected('A', 'D')).toBe(true);
    expect(dsu.countSets()).toBe(2); // B is still isolated
  });

  test('large number of unions', () => {
    const elements = Array.from({ length: 20 }, (_, i) => i);
    const dsu = new DSU(elements);
    for (let i = 0; i < 19; i++) dsu.union(i, i + 1);
    expect(dsu.countSets()).toBe(1);
    expect(dsu.connected(0, 19)).toBe(true);
  });
});
