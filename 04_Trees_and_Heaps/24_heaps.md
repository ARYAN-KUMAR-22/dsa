# 📚 Heaps: Priority Queues & Efficient Sorting

## 📋 Table of Contents
1. [Introduction](#introduction)
2. [Heap Properties](#heap-properties)
3. [Max Heap vs Min Heap](#max-heap-vs-min-heap)
4. [Array Representation](#array-representation)
5. [Heap Operations](#heap-operations)
6. [Heapify](#heapify)
7. [Heap Sort](#heap-sort)
8. [Priority Queue](#priority-queue)
9. [Code Examples](#code-examples)
10. [Applications](#applications)

---

## Introduction

A **Heap** is a complete binary tree that satisfies the **heap property**, typically used to efficiently implement priority queues and sorting algorithms.

### Key Characteristics
- **Complete Binary Tree**: All levels except possibly the last are completely filled
- **Heap Property**: Parent-child ordering (either max or min)
- **Array-Based**: Efficiently stored sequentially
- **Time Efficient**: O(log n) insertion/deletion, O(1) peek

### Real-World Applications
- **Priority Queues**: Task scheduling, Dijkstra's algorithm
- **Heap Sort**: O(n log n) sorting algorithm
- **Median Finding**: Using min-max heaps
- **Huffman Coding**: Building optimal prefix codes
- **Load Balancing**: Distributing tasks by priority
- **Stream Processing**: Finding k largest/smallest elements

---

## Heap Properties

### Definition
A binary tree is a **max heap** if:
$$\text{parent} \geq \text{left\_child} \text{ AND } \text{parent} \geq \text{right\_child}$$

A binary tree is a **min heap** if:
$$\text{parent} \leq \text{left\_child} \text{ AND } \text{parent} \leq \text{right\_child}$$

### Properties
1. **Complete Binary Tree**: Height = $\lfloor \log_2(n) \rfloor + 1$
2. **Parent-Child Ordering**: Immediate parent-child only (not global sorted)
3. **Efficient Storage**: Array representation with index formulas

### Counterexample - NOT a heap
```
        10
       /  \
      9    20      ✗ Not a max heap!
     /            (20 > 10)
    8
```

---

## Max Heap vs Min Heap

### Max Heap
```
          50
        /    \
      40      30
     / \      / \
    10  20   5   15

Property: Parent ≥ Children
Use: Find maximum element efficiently
```

### Min Heap
```
          5
        /    \
      10      15
     / \      / \
    20  30   40  50

Property: Parent ≤ Children
Use: Find minimum element efficiently (Priority queues)
```

### Comparison

| Property | Max Heap | Min Heap |
|----------|----------|----------|
| Root | Maximum | Minimum |
| Insertion | O(log n) | O(log n) |
| Extract Max/Min | O(log n) | O(log n) |
| Peek | O(1) | O(1) |
| Use Case | Priority queues (highest first) | Priority queues (lowest first) |

---

## Array Representation

### Index Formulas
For a node at index $i$ (0-indexed):

$$\text{Parent}(i) = \left\lfloor \frac{i-1}{2} \right\rfloor$$
$$\text{Left Child}(i) = 2i + 1$$
$$\text{Right Child}(i) = 2i + 2$$

### Visual Example

```
Heap Tree:          Array Representation:
        50          Index: 0  1  2  3  4  5  6
       /  \         Array: [50, 40, 30, 10, 20, 5, 15]
      40  30
     / \ / \
    10 20 5 15

Index relationships:
- Node 0 (50): children at 1 (40), 2 (30)
- Node 1 (40): children at 3 (10), 4 (20)
- Node 2 (30): children at 5 (5), 6 (15)
- Node 3 (10): parent at (3-1)/2 = 1 (40) ✓
```

---

## Heap Operations

### 1. Insert (Build Heap)

**Algorithm: "Bubble Up"**
```cpp
void insert(vector<int>& heap, int value) {
    heap.push_back(value);
    int index = heap.size() - 1;
    
    // Bubble up: compare with parent and swap if needed
    while (index > 0) {
        int parent = (index - 1) / 2;
        if (heap[index] > heap[parent]) {
            swap(heap[index], heap[parent]);
            index = parent;
        } else {
            break;
        }
    }
}
```

**Time Complexity**: O(log n)

### 2. Extract Max (Remove Root for Max Heap)

**Algorithm: "Bubble Down" / "Heapify"**
```cpp
int extractMax(vector<int>& heap) {
    if (heap.empty()) return -1;
    
    int max = heap[0];
    heap[0] = heap.back();
    heap.pop_back();
    
    // Bubble down: compare with children and swap with larger
    int index = 0;
    while (true) {
        int largest = index;
        int left = 2 * index + 1;
        int right = 2 * index + 2;
        
        if (left < heap.size() && heap[left] > heap[largest])
            largest = left;
        if (right < heap.size() && heap[right] > heap[largest])
            largest = right;
        
        if (largest != index) {
            swap(heap[index], heap[largest]);
            index = largest;
        } else {
            break;
        }
    }
    
    return max;
}
```

**Time Complexity**: O(log n)

### 3. Peek (Get Max without removal)

```cpp
int peek(const vector<int>& heap) {
    return heap.empty() ? -1 : heap[0];
}
```

**Time Complexity**: O(1)

---

## Heapify

### Build Heap from Array

**Naive approach**: Insert each element - O(n log n)

**Optimal approach**: Heapify bottom-up - O(n)

```cpp
void buildHeap(vector<int>& arr) {
    int n = arr.size();
    
    // Start from last non-leaf node and heapify downward
    for (int i = n / 2 - 1; i >= 0; i--) {
        heapifyDown(arr, i);
    }
}

void heapifyDown(vector<int>& heap, int index) {
    int largest = index;
    int left = 2 * index + 1;
    int right = 2 * index + 2;
    
    if (left < heap.size() && heap[left] > heap[largest])
        largest = left;
    if (right < heap.size() && heap[right] > heap[largest])
        largest = right;
    
    if (largest != index) {
        swap(heap[index], heap[largest]);
        heapifyDown(heap, largest);
    }
}
```

**Time Complexity Analysis**:
- Nodes at height $h$: $2^h$
- Work per node: $h$ comparisons
- Total: $\sum_{h=0}^{\log n} 2^h \cdot (\log n - h) = O(n)$

### Why O(n) not O(n log n)?
Most nodes are near leaves (height 0-1), so do minimal work. Few nodes at top do log n work but are few in number.

---

## Heap Sort

### Algorithm
1. Build max heap from array - O(n)
2. Extract max n times - O(n log n)

```cpp
void heapSort(vector<int>& arr) {
    int n = arr.size();
    
    // Build max heap
    for (int i = n / 2 - 1; i >= 0; i--)
        heapifyDown(arr, i, n);
    
    // Extract elements one by one
    for (int i = n - 1; i > 0; i--) {
        swap(arr[0], arr[i]);
        heapifyDown(arr, 0, i);
    }
}

void heapifyDown(vector<int>& arr, int index, int size) {
    int largest = index;
    int left = 2 * index + 1;
    int right = 2 * index + 2;
    
    if (left < size && arr[left] > arr[largest])
        largest = left;
    if (right < size && arr[right] > arr[largest])
        largest = right;
    
    if (largest != index) {
        swap(arr[index], arr[largest]);
        heapifyDown(arr, largest, size);
    }
}
```

**Time Complexity**: O(n log n)  
**Space Complexity**: O(1) - in-place

### Heap Sort Example
```
Initial: [3, 2, 1, 6, 0, 5]

After heapify: [6, 3, 5, 2, 0, 1] (max heap)

Extraction:
Step 1: Swap 6↔1 → [1, 3, 5, 2, 0, | 6] → Heapify
        Result: [5, 3, 1, 2, 0, | 6]
Step 2: Swap 5↔0 → [0, 3, 1, 2, | 5, 6] → Heapify
        Result: [3, 2, 1, 0, | 5, 6]
...
Final: [0, 1, 2, 3, 5, 6] ✓ Sorted!
```

---

## Priority Queue

### Implementation using Max Heap

```cpp
class MaxPriorityQueue {
private:
    vector<int> heap;
    
public:
    void push(int value) {
        heap.push_back(value);
        int index = heap.size() - 1;
        
        while (index > 0) {
            int parent = (index - 1) / 2;
            if (heap[index] > heap[parent]) {
                swap(heap[index], heap[parent]);
                index = parent;
            } else {
                break;
            }
        }
    }
    
    int top() {
        return heap.empty() ? -1 : heap[0];
    }
    
    void pop() {
        if (heap.empty()) return;
        
        heap[0] = heap.back();
        heap.pop_back();
        
        int index = 0;
        while (true) {
            int largest = index;
            int left = 2 * index + 1;
            int right = 2 * index + 2;
            
            if (left < heap.size() && heap[left] > heap[largest])
                largest = left;
            if (right < heap.size() && heap[right] > heap[largest])
                largest = right;
            
            if (largest != index) {
                swap(heap[index], heap[largest]);
                index = largest;
            } else {
                break;
            }
        }
    }
    
    bool empty() {
        return heap.empty();
    }
};
```

---

## Code Examples

### Complete Max Heap Implementation

```cpp
#include <iostream>
#include <vector>
using namespace std;

class MaxHeap {
private:
    vector<int> heap;
    
    void heapifyUp(int index) {
        while (index > 0) {
            int parent = (index - 1) / 2;
            if (heap[index] > heap[parent]) {
                swap(heap[index], heap[parent]);
                index = parent;
            } else {
                break;
            }
        }
    }
    
    void heapifyDown(int index) {
        while (true) {
            int largest = index;
            int left = 2 * index + 1;
            int right = 2 * index + 2;
            
            if (left < heap.size() && heap[left] > heap[largest])
                largest = left;
            if (right < heap.size() && heap[right] > heap[largest])
                largest = right;
            
            if (largest != index) {
                swap(heap[index], heap[largest]);
                index = largest;
            } else {
                break;
            }
        }
    }
    
public:
    void insert(int value) {
        heap.push_back(value);
        heapifyUp(heap.size() - 1);
    }
    
    int getMax() {
        if (heap.empty()) return -1;
        return heap[0];
    }
    
    int extractMax() {
        if (heap.empty()) return -1;
        
        int max = heap[0];
        heap[0] = heap.back();
        heap.pop_back();
        
        if (!heap.empty())
            heapifyDown(0);
        
        return max;
    }
    
    void buildHeap(vector<int>& arr) {
        heap = arr;
        int n = heap.size();
        for (int i = n / 2 - 1; i >= 0; i--) {
            heapifyDown(i);
        }
    }
    
    void display() {
        for (int val : heap)
            cout << val << " ";
        cout << endl;
    }
};

int main() {
    MaxHeap heap;
    
    // Insert elements
    heap.insert(10);
    heap.insert(5);
    heap.insert(20);
    heap.insert(2);
    heap.insert(8);
    
    cout << "Heap after insertions: ";
    heap.display();  // Output: 20 10 8 2 5
    
    cout << "Max element: " << heap.getMax() << endl;  // 20
    
    cout << "Extracting max: " << heap.extractMax() << endl;  // 20
    cout << "Heap after extraction: ";
    heap.display();  // Output: 10 5 8 2
    
    // Build heap from array
    vector<int> arr = {3, 1, 4, 1, 5, 9, 2, 6};
    heap.buildHeap(arr);
    cout << "Heap from array: ";
    heap.display();  // Output: 9 6 5 1 1 3 2 4
    
    return 0;
}
```

### Heap Sort Implementation

```cpp
void heapSort(vector<int>& arr) {
    int n = arr.size();
    
    // Build max heap
    for (int i = n / 2 - 1; i >= 0; i--) {
        heapifyDown(arr, i, n);
    }
    
    // Extract elements
    for (int i = n - 1; i > 0; i--) {
        swap(arr[0], arr[i]);
        heapifyDown(arr, 0, i);
    }
}

void heapifyDown(vector<int>& arr, int index, int size) {
    int largest = index;
    int left = 2 * index + 1;
    int right = 2 * index + 2;
    
    if (left < size && arr[left] > arr[largest])
        largest = left;
    if (right < size && arr[right] > arr[largest])
        largest = right;
    
    if (largest != index) {
        swap(arr[index], arr[largest]);
        heapifyDown(arr, largest, size);
    }
}

int main() {
    vector<int> arr = {5, 3, 8, 1, 2};
    heapSort(arr);
    
    cout << "Sorted array: ";
    for (int x : arr)
        cout << x << " ";  // Output: 1 2 3 5 8
    cout << endl;
    
    return 0;
}
```

---

## Applications

### 1. Dijkstra's Algorithm
Uses min-heap to efficiently select node with minimum distance.

### 2. Huffman Coding
Builds optimal binary tree for data compression using min-heap.

### 3. Heap Sort
General-purpose sorting: O(n log n), O(1) space.

### 4. Median in Stream
Use min-heap and max-heap to track median of streaming elements.

### 5. K Largest Elements
Use min-heap of size k to find k largest elements in O(n log k).

### 6. Task Scheduling
Process tasks by priority using heap.

---

## Key Takeaways

✅ **Heap is a complete binary tree** - Efficient array representation  
✅ **O(log n) insertion/deletion** - Heapify operations efficient  
✅ **O(n) build heap** - Better than inserting n times  
✅ **O(n log n) sorting** - Heap sort optimal comparison sort  
✅ **O(1) space** - Can sort in-place  
✅ **Priority queues** - Natural application of heaps  

| Operation | Time |
|-----------|------|
| Insert | O(log n) |
| Extract Max | O(log n) |
| Peek Max | O(1) |
| Build Heap | O(n) |
| Heap Sort | O(n log n) |

---

## Practice Problems

1. **LeetCode 215**: Kth Largest Element - Use heap to find kth largest
2. **LeetCode 295**: Find Median from Data Stream - Use two heaps
3. **LeetCode 347**: Top K Frequent Elements - Heap with frequency
4. **LeetCode 703**: Kth Largest in Stream - Min-heap of size k
5. **LeetCode 253**: Meeting Rooms II - Min-heap for intervals

---

## Next Steps

1. **Implement min-heap** - All operations with min property
2. **Solve heap problems** - Practice with LeetCode problems
3. **Learn heapsort** - Implement and understand trade-offs
4. **Priority queue usage** - Dijkstra, Prim's algorithms
5. **Advanced heaps** - Fibonacci heap, binomial heap

**Remember**: Heaps are fundamental to efficient algorithms! 🎯