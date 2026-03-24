# Tree Data Structure: Terminology & Concepts

A **Tree** is a hierarchical data structure consisting of nodes connected by edges. It is a non-linear data structure, unlike arrays, linked lists, stacks, and queues.

---

## 🌳 Visual Representation (As per your Image)

```mermaid
graph TD
    %% Level 1
    A((A)) --- B((B))
    A --- C((C))
    A --- D((D))
    
    %% Level 2 to 3
    B --- E((E))
    B --- F((F))
    
    D --- G((G))
    D --- H((H))
    D --- I((I))
    
    %% Level 3 to 4
    F --- J((J))
    F --- K((K))
    
    H --- L((L))
    
    %% Level 4 to 5
    J --- M((M))
    
    L --- N((N))
    L --- O((O))

    %% Level Indicators (Subgraphs for alignment)
    subgraph Level1 [Level 1]
        A
    end
    subgraph Level2 [Level 2]
        B
        C
        D
    end
    subgraph Level3 [Level 3]
        E
        F
        G
        H
        I
    end
    subgraph Level4 [Level 4]
        J
        K
        L
    end
    subgraph Level5 [Level 5]
        M
        N
        O
    end

    %% Legend & Styling
    style A fill:#fdf,stroke:#333
    style B fill:#fdf,stroke:#333
    style D fill:#fdf,stroke:#333
    style F fill:#fdf,stroke:#333
    style H fill:#fdf,stroke:#333
    style J fill:#fdf,stroke:#333
    style L fill:#fdf,stroke:#333
    
    style C fill:#fff,stroke:#333
    style E fill:#fff,stroke:#333
    style G fill:#fff,stroke:#333
    style I fill:#fff,stroke:#333
    style K fill:#fff,stroke:#333
    style M fill:#fff,stroke:#333
    style N fill:#fff,stroke:#333
    style O fill:#fff,stroke:#333

    %% Annotations
    A -- "Root" --> A
```
```

---

## 📝 Key Terminology

### 1. Basic Components
- **Node**: Each element in a tree containing data and links to other nodes.
- **Root**: The topmost node of the tree. It has no parent. (Example: Node **A**)
- **Edge**: The connection between two nodes. If there are **N** nodes, there are **N-1** edges.
- **Parent**: A node that has child nodes. (Example: **A** is the parent of **B** and **C**)
- **Child**: A node that has a parent node. (Example: **B** and **C** are children of **A**)
- **Siblings**: Nodes that share the same parent. (Example: **B** and **C** are siblings)

### 2. Node Types
- **Leaf (External Node)**: A node with no children. (Example: **D, F, G, H**)
- **Internal Node**: A node with at least one child. (Example: **A, B, C, E**)
- **Ancestor**: Any node on the path from the root to that node (including parent and grandparent).
- **Descendant**: Any node in the subtree rooted at that node.

### 3. Tree Measurements
- **Degree of a Node**: The total number of children of that node. (Example: Degree of **B** is 2)
- **Degree of a Tree**: The maximum degree of any node in the tree.
- **Level**: The number of edges from the root to the node. (Root is at Level 0)
- **Depth**: The number of edges from the root to the node. (Same as Level)
- **Height of a Node**: The number of edges on the longest path from the node to a leaf.
- **Height of a Tree**: The height of the root node.

### 4. Structure Concepts
- **Subtree**: A portion of the tree that can be viewed as a complete tree itself.
- **Path**: A sequence of nodes and edges connecting a node with a descendant.
- **Forest**: A collection of disjoint trees.

---

## 💡 Quick Summary Table

| Term | Description | Position/Example |
| :--- | :--- | :--- |
| **Root** | Topmost node | Node A |
| **Leaf** | No children | Node D, F, G, H |
| **Height** | Max edges to leaf | Height of A = 3 |
| **Depth** | Edges from root | Depth of E = 2 |
| **Degree** | Number of children | Degree of E = 2 |
